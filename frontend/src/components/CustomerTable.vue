<template>
  <q-card class="q-pa-md q-mt-xl shadow-1" style="max-width: 1000px; margin: 40px auto;">
    <h2 class="text-h6 q-mb-md">📋 Kundenliste</h2>
    <q-table :rows="customers" :columns="columns" row-key="id" flat bordered>
      <template v-slot:body-cell-actions="props">
        <q-btn dense icon="edit" color="primary" @click="$emit('edit', props.row)" round size="sm" />
        <q-btn dense icon="delete" color="negative" @click="deleteCustomer(props.row.id)" round size="sm" />

      </template>
    </q-table>
  </q-card>
</template>

<script setup>
defineProps({
  customers: Array,
  onEdit: Function
})

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
</script>

<style scoped>
.q-card {
  border-radius: 12px;
}
</style>
