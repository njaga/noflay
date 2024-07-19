<template>
    <Modal :show="true" @close="$emit('close')">
      <template #title>
        Enregistrer une dépense
      </template>
      <template #content>
        <form @submit.prevent="submitForm">
          <div class="grid grid-cols-1 gap-6">
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <input type="text" id="description" v-model="form.description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
              <input type="number" id="amount" v-model="form.amount" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
              <input type="date" id="date" v-model="form.date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
          </div>
        </form>
      </template>
      <template #footer>
        <button @click="$emit('close')" class="btn-secondary mr-2">Annuler</button>
        <button @click="submitForm" class="btn-primary">Enregistrer</button>
      </template>
    </Modal>
  </template>

  <script setup>
  import { ref } from 'vue';
  import Modal from '@/Components/Modal.vue';

  const props = defineProps({
    landlord: {
      type: Object,
      required: true
    }
  });

  const emit = defineEmits(['close', 'add']);

  const form = ref({
    description: '',
    amount: 0,
    date: new Date().toISOString().substr(0, 10),
    type: 'dépense'
  });

  const submitForm = () => {
    emit('add', { ...form.value, landlord_id: props.landlord.id });
  };
  </script>
