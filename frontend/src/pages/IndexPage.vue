<template>
  <div class="q-pa-md">
    <!-- Tabs zur Navigation zwischen Kunden-Hinzufügen und -Suchen -->
    <q-tabs v-model="tab" class="text-primary">
      <q-tab name="add" label="🆕 Kunde hinzufügen" />
      <q-tab name="search" label="🔍 Kunde suchen" />
    </q-tabs>

    <q-separator class="q-my-md" />

    <!-- Inhalt je nach gewähltem Tab -->
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="add">
        <!-- Formular zum Hinzufügen oder Bearbeiten eines Kunden -->
        <CustomerForm
          v-model="form"
          :editing-id="editingId"
          @submit="submitForm"
          @cancel="resetForm"
        />
      </q-tab-panel>

      <q-tab-panel name="search">
        <!-- Tabelle zur Kundensuche und -bearbeitung -->
        <CustomerTable
          :customers="customers"
          @edit="editCustomer"
          @delete-request="confirmDeleteCustomer"
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
const tab = ref('add') // Start-Tab auf "add"
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

/* Lädt die Kundenliste vom Server */
const fetchCustomers = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/customers')
    customers.value = response.data.customers
  } catch (err) {
    console.error('Fehler beim Laden der Kundenliste:', err)
  }
}

/* Speichern oder Aktualisieren eines Kunden */
const submitForm = async (formData) => {
  try {
    if (editingId.value) {
      await axios.put(`http://localhost:8000/api/customers/${editingId.value}`, formData)
      $q.notify({
        type: 'positive',
        message: '✅ Kunde erfolgreich aktualisiert!',
        timeout: 3000,
        position: 'center',
        classes: 'text-h6 bg-green text-white'
      })
    } else {
      await axios.post('http://localhost:8000/api/customers', formData)
      $q.notify({
        type: 'positive',
        message: '✅ Kunde erfolgreich gespeichert!',
        timeout: 3000,
        position: 'center',
        classes: 'text-h6 bg-primary text-white'
      })
    }

    resetForm()
    await fetchCustomers()
    tab.value = 'search' // Nach Speichern zur Suchseite wechseln
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

/* Öffnet einen Bestätigungsdialog vor dem Löschen */
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

/* Löscht einen Kunden endgültig */
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

/* Befüllt das Formular mit Daten eines Kunden zum Bearbeiten */
const editCustomer = (customer) => {
  form.value = { ...customer }
  editingId.value = customer.id
  tab.value = 'add' // Wechsel automatisch zum "add"-Tab
}

/* Setzt das Formular zurück und entfernt den Bearbeitungsmodus */
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

// Initiales Laden der Kunden
onMounted(fetchCustomers)
</script>

<style scoped>
.q-pa-md {
  padding-bottom: 100px;
}  

</style>

