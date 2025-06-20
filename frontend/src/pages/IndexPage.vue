<template>
  <div class="q-pa-md">
    <q-card class="q-pa-lg shadow-2 rounded-borders" style="max-width: 800px; margin: auto;">
      <h2 class="text-h5 q-mb-md">📝 Kunde hinzufügen / bearbeiten</h2>
      <q-form @submit.prevent="submitForm">
        <q-input v-model="form.nachname" label="Nachname" class="q-mb-sm" filled required />
        <q-input v-model="form.vorname" label="Vorname" class="q-mb-sm" filled required />
        <q-input v-model="form.email" label="E-Mail" class="q-mb-sm" filled type="email" required />
        <q-input v-model="form.telefon" label="Telefon" class="q-mb-sm" filled required />
        <q-input v-model="form.strasse" label="Straße" class="q-mb-sm" filled required />
        <q-input v-model="form.plz" label="PLZ" class="q-mb-sm" filled required />
        <q-input v-model="form.ort" label="Ort" class="q-mb-sm" filled required />

        <div class="row q-mt-md">
          <q-btn :label="editingId ? 'Aktualisieren' : 'Speichern'" color="primary" type="submit" class="q-mr-sm" />
          <q-btn v-if="editingId" label="Abbrechen" color="secondary" flat @click="resetForm" />
        </div>
      </q-form>
    </q-card>

    <q-card class="q-pa-md q-mt-xl shadow-1" style="max-width: 1000px; margin: 40px auto;">
      <h2 class="text-h6 q-mb-md">📋 Kundenliste</h2>
      <q-table :rows="customers" :columns="columns" row-key="id" flat bordered>
        <template v-slot:body-cell-actions="props">
          <q-btn dense icon="edit" color="primary" @click="editCustomer(props.row)" round size="sm" class="q-mr-xs" />
          <q-btn dense icon="delete" color="negative" @click="deleteCustomer(props.row.id)" round size="sm" />

        </template>
      </q-table>
    </q-card>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const form = ref({
  nachname: '',
  vorname: '',
  email: '',
  telefon: '',
  strasse: '',
  plz: '',
  ort: '',
})

const editingId = ref(null)
const customers = ref([])

const columns = [
  { name: 'nachname', label: 'Nachname', field: 'nachname' },
  { name: 'vorname', label: 'Vorname', field: 'vorname' },
  { name: 'email', label: 'E-Mail', field: 'email' },
  { name: 'telefon', label: 'Telefon', field: 'telefon' },
  { name: 'strasse', label: 'Straße', field: 'strasse' },
  { name: 'plz', label: 'PLZ', field: 'plz' },
  { name: 'ort', label: 'Ort', field: 'ort' },
  { name: 'actions', label: 'Aktion', field: 'actions', sortable: false },
]

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
    ort: '',
  }
  editingId.value = null
}

onMounted(fetchCustomers)
</script>

<style scoped>
.q-card {
  border-radius: 12px;
}
</style>
