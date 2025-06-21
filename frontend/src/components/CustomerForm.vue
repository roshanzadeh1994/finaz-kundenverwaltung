<template>
  <q-card class="q-pa-lg shadow-2 rounded-borders" style="max-width: 800px; margin: auto;">
    <h2 class="text-h5 q-mb-md">📝 Kunde hinzufügen / bearbeiten</h2>
    
    <!-- Formular zur Eingabe oder Bearbeitung eines Kunden -->
    <q-form @submit.prevent="onSubmit">
      <!-- Pflichtfelder für Kundendaten -->
      <q-input v-model="localForm.nachname" label="Nachname" class="q-mb-sm" filled required />
      <q-input v-model="localForm.vorname" label="Vorname" class="q-mb-sm" filled required />
      <q-input v-model="localForm.email" label="E-Mail" class="q-mb-sm" filled type="email" required />
      <q-input v-model="localForm.telefon" label="Telefon" class="q-mb-sm" filled required />
      <q-input v-model="localForm.strasse" label="Straße" class="q-mb-sm" filled required />
      <q-input v-model="localForm.plz" label="PLZ" class="q-mb-sm" filled required />
      <q-input v-model="localForm.ort" label="Ort" class="q-mb-sm" filled required />

      <div class="row q-mt-md">
        <!-- Speichern- oder Aktualisieren-Button -->
        <q-btn :label="editingId ? 'Aktualisieren' : 'Speichern'" color="primary" type="submit" class="q-mr-sm" />
        
        <!-- Abbrechen-Button wird nur beim Editieren gezeigt -->
        <q-btn v-if="editingId" label="Abbrechen" color="secondary" flat @click="onCancel" />
      </div>
    </q-form>
  </q-card>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

// Eingabeparameter aus dem Eltern-Component
const props = defineProps({
  modelValue: Object,     // Daten des Formulars
  editingId: Number       // Gibt an, ob ein bestehender Kunde bearbeitet wird
})

// Events zur Kommunikation mit dem Eltern-Component
const emits = defineEmits(['submit', 'cancel', 'update:modelValue'])

const localForm = ref({})

// Initialisiere lokale Kopie der Kundendaten beim Laden
onMounted(() => {
  localForm.value = { ...props.modelValue }
})

// Überwacht Änderungen im Formular und meldet diese an den Parent
watch(
  localForm,
  (newVal) => {
    emits('update:modelValue', newVal)
  },
  { deep: true }
)

// Beim Absenden wird das Formular an den Parent übergeben
const onSubmit = () => {
  emits('submit', { ...localForm.value })
}

// Bricht den Bearbeitungsmodus ab
const onCancel = () => {
  emits('cancel')
}
</script>

<style scoped>
.q-card {
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
}

.q-input {
  font-size: 16px;
}

.q-btn {
  font-size: 16px;
  padding: 10px 16px;
}
</style>
