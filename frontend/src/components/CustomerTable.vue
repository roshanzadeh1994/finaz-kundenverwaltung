

<template>
  <q-card class="q-pa-md q-mt-xl shadow-1" style="max-width: 1400px; width: 110%; margin: 40px auto;">
    <h2 class="text-h6 q-mb-md">📋 Kundenliste</h2>

    <div class="row q-col-gutter-md q-mb-md">
      <q-input filled v-model="search.id" label="🔍 ID" type="number" dense @keyup.enter="applySearch" />
      <q-input filled v-model="search.email" label="🔍 E-Mail" dense @keyup.enter="applySearch" />
      <q-input filled v-model="search.name" label="🔍 Nachname oder Vorname" dense @keyup.enter="applySearch" />
      
      <q-btn label="Zurücksetzen" flat @click="resetSearch" />
    </div>

    <q-table :rows="filteredCustomers"
                    :columns="columns" row-key="id" flat bordered class="q-mt-md shadow-3 big-table" table-class="text-left"
                    table-header-class="bg-primary text-white text-bold" dense="false" wrap-cells>
    <template v-slot:body-cell-actions="props">
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
                            />

        </template>




    </q-table>
  </q-card>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  customers: Array
})

const emit = defineEmits(['edit', 'delete-request'])



const search = ref({
  id: '',
  email: '',
  name: ''
})

const filteredCustomers = computed(() => {
  return props.customers.filter(customer => {
    const matchId = search.value.id
      ? customer.id === parseInt(search.value.id)
      : true

    const matchEmail = search.value.email
      ? customer.email.toLowerCase().includes(search.value.email.toLowerCase())
      : true

    const matchName = search.value.name
      ? (
          customer.nachname.toLowerCase().includes(search.value.name.toLowerCase()) ||
          customer.vorname.toLowerCase().includes(search.value.name.toLowerCase())
        )
      : true

    return matchId && matchEmail && matchName
  })
})

const applySearch = () => {
  // The actual filtering is reactive and handled by computed above
}

const resetSearch = () => {
  search.value = {
    id: '',
    email: '',
    name: ''
  }
}
  
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

.q-table th, .q-table td {
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
</style>
