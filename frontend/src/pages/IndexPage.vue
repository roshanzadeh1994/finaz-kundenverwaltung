<template>
  <div class="q-pa-md">
    <q-tabs v-model="tab" class="text-primary">
      <q-tab name="add" label="🆕 Kunde hinzufügen" />
      <q-tab name="search" label="🔍 Kunde suchen" />
    </q-tabs>

    <q-separator class="q-my-md" />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="add">
        <CustomerForm
          v-model="form"
          :editing-id="editingId"
          @submit="submitForm"
          @cancel="resetForm"
        />  

      </q-tab-panel>

      <q-tab-panel name="search">
        <CustomerTable
            :customers="customers"
            @edit="editCustomer"
            @delete-request="confirmDeleteCustomer"
          />

        />
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

import CustomerForm from 'src/components/CustomerForm.vue'
import CustomerTable from 'src/components/CustomerTable.vue'

import { useQuasar } from 'quasar'


const $q = useQuasar()
const tab = ref('add') // default tab

const customers = ref([])
const editingId = ref(null)

const form = ref({
  nachname: '',
  vorname: '',
  email: '',
  telefon: '',
  strasse: '',
  plz: '',
  ort: ''
})

const fetchCustomers = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/customers')
    customers.value = response.data.customers
  } catch (err) {
    console.error('Fehler beim Laden der Kundenliste:', err)
  }
}

const submitForm = async (formData) => {
  try {
    if (editingId.value) {
      await axios.put(`http://localhost:8000/api/customers/${editingId.value}`, formData)
      $q.notify({
        type: 'positive',
        message: '✅ Kunde erfolgreich aktualisiert!',
        timeout: 3000,
        position: 'center',     // نمایش وسط صفحه
        classes: 'text-h6 bg-green text-white'  // استایل بزرگ‌تر و رنگی
      })
    } else {
      await axios.post('http://localhost:8000/api/customers', formData)
      $q.notify({
        type: 'positive',
        message: '✅ Kunde erfolgreich gespeichert!',
        timeout: 3000,
        position: 'center',     // نمایش وسط صفحه
        classes: 'text-h6 bg-primary text-white'  // استایل بزرگ‌تر و رنگی
      })
    }

    resetForm()
    await fetchCustomers()

    // انتقال به تب سرچ
    tab.value = 'search'
  } catch (err) {
    $q.notify({
      type: 'negative',
      message: '❌ Fehler beim Speichern',
      timeout: 4000,
      position: 'center',
      classes: 'text-h6 bg-red text-white'
    })
    console.error(err)
  }
}



const confirmDeleteCustomer = async (id) => {
  $q.dialog({
    title: '❗ Kunde löschen',
    message: 'Bist du sicher, dass du diesen Kunden löschen möchtest?',
    persistent: true,
    ok: {
      label: 'Ja, löschen',
      color: 'negative'
    },
    cancel: {
      label: 'Abbrechen',
      color: 'grey',
      flat: true
    }
  }).onOk(async () => {
    await deleteCustomer(id)
  })
}



const deleteCustomer = async (id) => {
  try {
    await axios.delete(`http://localhost:8000/api/customers/${id}`)
    $q.notify({
      type: 'positive',
      message: '✅ Kunde wurde erfolgreich gelöscht.',
      timeout: 3000,
      position: 'top'
    })
    await fetchCustomers()
  } catch (err) {
    $q.notify({
      type: 'negative',
      message: '❌ Fehler beim Löschen',
      timeout: 3000
    })
    console.error('Fehler beim Löschen:', err)
  }
}


// const deleteCustomer = async (id) => {
//   if (confirm('Möchtest du diesen Kunden wirklich löschen?')) {
//     try {
//       await axios.delete(`http://localhost:8000/api/customers/${id}`)
//       alert('Kunde erfolgreich gelöscht ✅')
//       await fetchCustomers()
//     } catch (err) {
//   console.error('Fehler beim Löschen:', err)
//   alert('Fehler beim Löschen')
// }

//   }
// }





// const deleteCustomer = async (id) => {
//   try {
//     await $q.dialog({
//       title: '❗ Kunde löschen',
//       message: 'Bist du sicher, dass du diesen Kunden löschen möchtest?',
//       persistent: true,
//       ok: {
//         label: 'Ja, löschen',
//         color: 'negative',
//         flat: false
//       },
//       cancel: {
//         label: 'Abbrechen',
//         color: 'grey',
//         flat: true
//       }
//     })

//     // اگر کاربر OK رو زد:
//     await axios.delete(`http://localhost:8000/api/customers/${id}`)
//     $q.notify({
//       type: 'positive',
//       message: '✅ Kunde wurde erfolgreich gelöscht.',
//       timeout: 3000,
//       position: 'top'
//     })
//     await fetchCustomers()

//   } catch (err) {
//     // اگر کاربر Cancel زد یا خطا پیش اومد
//     console.warn('Löschen abgebrochen oder Fehler:', err)
//     $q.notify({
//       type: 'negative',
//       message: '❌ Löschen wurde abgebrochen oder fehlgeschlagen.',
//       timeout: 3000,
//       position: 'top'
//     })
//   }
// }




// const deleteCustomer = async (id) => {
//   try {
//     await $q.dialog({
//       title: '❗ Kunde löschen',
//       message: 'Bist du sicher, dass du diesen Kunden löschen möchtest?',
//       persistent: true,
//       ok: {
//         label: 'Ja, löschen',
//         color: 'negative',
//         flat: false
//       },
//       cancel: {
//         label: 'Abbrechen',
//         color: 'grey',
//         flat: true
//       }
//     }).onOk(() => {
//       // این تابع فقط صدا زده میشه وقتی کاربر OK رو بزنه
//       return true // بدون این، promise کامل نمیشه
//     })

//     // فقط وقتی که کاربر OK رو بزنه، این بخش اجرا میشه
//     await axios.delete(`http://localhost:8000/api/customers/${id}`)
//     $q.notify({
//       type: 'positive',
//       message: '✅ Kunde wurde erfolgreich gelöscht.',
//       timeout: 3000,
//       position: 'top'
//     })
//     await fetchCustomers()

//   } catch (err) {
//     // اگر کاربر Cancel رو بزنه یا ارور بیاد
//     console.warn('Löschen abgebrochen oder fehlgeschlagen:', err)
//   }
// }



const editCustomer = (customer) => {
  form.value = { ...customer }
  editingId.value = customer.id
  tab.value = 'add' // beim Editieren automatisch auf Add-Form wechseln
}

const resetForm = () => {
  form.value = {
    nachname: '',
    vorname: '',
    email: '',
    telefon: '',
    strasse: '',
    plz: '',
    ort: ''
  }
  editingId.value = null
}

onMounted(fetchCustomers)
</script>

<style scoped>
.q-pa-md {
  padding-bottom: 100px;
}
</style>
