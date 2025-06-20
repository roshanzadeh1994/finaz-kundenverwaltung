<template>
  <q-card class="q-pa-lg shadow-2 rounded-borders" style="max-width: 800px; margin: auto;">
    <h2 class="text-h5 q-mb-md">📝 Kunde hinzufügen / bearbeiten</h2>
    <q-form @submit.prevent="onSubmit">
      <q-input v-model="localForm.nachname" label="Nachname" class="q-mb-sm" filled required />
      <q-input v-model="localForm.vorname" label="Vorname" class="q-mb-sm" filled required />
      <q-input v-model="localForm.email" label="E-Mail" class="q-mb-sm" filled type="email" required />
      <q-input v-model="localForm.telefon" label="Telefon" class="q-mb-sm" filled required />
      <q-input v-model="localForm.strasse" label="Straße" class="q-mb-sm" filled required />
      <q-input v-model="localForm.plz" label="PLZ" class="q-mb-sm" filled required />
      <q-input v-model="localForm.ort" label="Ort" class="q-mb-sm" filled required />

      <div class="row q-mt-md">
        <q-btn :label="editingId ? 'Aktualisieren' : 'Speichern'" color="primary" type="submit" class="q-mr-sm" />
        <q-btn v-if="editingId" label="Abbrechen" color="secondary" flat @click="onCancel" />
      </div>
    </q-form>
  </q-card>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: Object,
  editingId: Number
})

const emits = defineEmits(['submit', 'cancel', 'update:modelValue'])

const localForm = ref({ ...props.modelValue })

watch(
  () => props.modelValue,
  (newVal) => {
    localForm.value = { ...newVal }
  },
  { deep: true }
)

const onSubmit = () => {
  emits('submit', { ...localForm.value })
}

const onCancel = () => {
  emits('cancel')
}
</script>

<style scoped>
.q-card {
  border-radius: 12px;
}
</style>
