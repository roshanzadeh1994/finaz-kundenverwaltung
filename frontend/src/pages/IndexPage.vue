<template>
  <div class="q-pa-md">
    <CustomerForm
      :form="form"
      :editing-id="editingId"
      @submit="submitForm"
      @cancel="resetForm"
    />
    
    <CustomerTable
      :customers="customers"
      @edit="editCustomer"
      @delete="deleteCustomer"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

import CustomerForm from 'src/components/CustomerForm.vue'
import CustomerTable from 'src/components/CustomerTable.vue'

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

const submitForm = async () => {
  try {
    if (editingId.value) {
      await axios.put(`http://localhost:8000/api/customers/${editingId.value}`, form.value)
      alert('Kunde erfolgreich aktualisiert ✅')
    } else {
      await axios.post('http://localhost:8000/api/customers', form.value)
      alert('Kunde erfolgreich gespeichert ✅')
    }

    resetForm()
    await fetchCustomers()
  } catch (err) {
    console.error('Fehler beim Speichern:', err)
    alert('Fehler beim Speichern')
  }
}

const editCustomer = (customer) => {
  form.value = { ...customer }
  editingId.value = customer.id
}


const deleteCustomer = async (id) => {
  if (confirm('Möchtest du diesen Kunden wirklich löschen?')) {
    try {
      await axios.delete(`http://localhost:8000/api/customers/${id}`)
      alert('Kunde erfolgreich gelöscht ✅')
      await fetchCustomers()
    } catch (err) {
      console.error('Fehler beim Löschen:', err)
      alert('Fehler beim Löschen')
    }
  }
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
