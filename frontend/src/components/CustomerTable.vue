<template>
  <q-card class="q-pa-md q-mt-xl shadow-1" style="max-width: 1200px; width: 90%; margin: 40px auto;">
    <h2 class="text-h6 q-mb-md">📋 Kundenliste</h2>

    <!-- Suchfelder für ID, E-Mail und Name -->
    <div class="row q-col-gutter-md q-mb-md">
      <q-input filled v-model="search.id" label="🔍 ID" type="number" dense @keyup.enter="applySearch" />
      <q-input filled v-model="search.email" label="🔍 E-Mail" dense @keyup.enter="applySearch" />
      <q-input filled v-model="search.name" label="🔍 Nachname und Vorname" dense @keyup.enter="applySearch" />
      
      <q-btn label="Zurücksetzen" flat @click="resetSearch" />
    </div>

    <!-- Tabelle zur Anzeige der Kunden -->
    <q-table
      :rows="filteredCustomers"
      :columns="columns"
      row-key="id"
      flat
      bordered
      class="q-mt-md shadow-3 big-table"
      table-class="text-left"
      table-header-class="bg-primary text-white text-bold"
      dense="false"
      wrap-cells
    >
      <!-- Aktionsspalte: Bearbeiten & Löschen -->
      <template v-slot:body-cell-actions="props">
        <div class="action-buttons">
          <q-btn
            dense
            icon="edit"
            color="primary"
            @click="() => $emit('edit', props.row)"
            round
            size="sm"
          />
          <q-btn
            dense
            icon="delete"
            color="negative"
            @click="() => emit('delete-request', props.row.id)"
            round
            size="sm"
            class="q-ml-sm"
          />
        </div>
      </template>
    </q-table>
  </q-card>
</template>

<script setup>
import { ref, computed } from 'vue'

// Eingabeparameter (Liste der Kunden)
const props = defineProps({
  customers: Array
})

// Events zur Bearbeitung oder Löschung eines Kunden
const emit = defineEmits(['edit', 'delete-request'])

// Suchparameter
const search = ref({
  id: '',
  email: '',
  name: ''
})

// Gefilterte Kunden basierend auf Suchbegriffen
const filteredCustomers = computed(() => {
  return props.customers.filter(customer => {
    const matchId = search.value.id
      ? customer.id === parseInt(search.value.id)
      : true

    const matchEmail = search.value.email
      ? customer.email.toLowerCase().includes(search.value.email.toLowerCase())
      : true

    const query = search.value.name.toLowerCase().trim()
    const fullName = `${customer.vorname} ${customer.nachname}`.toLowerCase()
    const reverseFullName = `${customer.nachname} ${customer.vorname}`.toLowerCase()

    const matchName = search.value.name
      ? (
          customer.vorname.toLowerCase().includes(query) ||
          customer.nachname.toLowerCase().includes(query) ||
          fullName.includes(query) ||
          reverseFullName.includes(query)
        )
      : true

    return matchId && matchEmail && matchName
  })
})

// Platzhalter für Enter-Taste (nicht notwendig, da computed)
const applySearch = () => {}

// Suchfelder zurücksetzen
const resetSearch = () => {
  search.value = {
    id: '',
    email: '',
    name: ''
  }
}

// Definition der Tabellenspalten
const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
  { name: 'nachname', label: 'Nachname', field: 'nachname' },
  { name: 'vorname', label: 'Vorname', field: 'vorname' },
  { name: 'email', label: 'E-Mail', field: 'email' },
  { name: 'telefon', label: 'Telefon', field: 'telefon' },
  { name: 'strasse', label: 'Straße', field: 'strasse' },
  { name: 'plz', label: 'PLZ', field: 'plz' },
  { name: 'ort', label: 'Ort', field: 'ort' },
  { name: 'actions', label: 'Aktion', field: 'actions', sortable: false },
]
</script>

<style scoped>
.q-card {
  border-radius: 16px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
  padding: 20px;
}

.q-table {
  font-size: 17px;
  line-height: 1.6;
}

.q-table th,
.q-table td {
  padding: 16px 14px;
  vertical-align: middle;
}

.q-table th {
  background-color: #1976d2;
  color: white;
  font-weight: bold;
  text-align: center;
}

.q-table td {
  text-align: center;
}

.action-buttons {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 15px;
}
</style>
