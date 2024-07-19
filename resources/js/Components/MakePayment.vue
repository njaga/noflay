<template>
    <Modal :show="true" @close="$emit('close')">
      <template #title>
        Effectuer un versement
      </template>
      <template #content>
        <form @submit.prevent="submitForm">
          <div class="grid grid-cols-1 gap-6">
            <div>
              <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
              <input type="number" id="amount" v-model="form.amount" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
              <input type="date" id="date" v-model="form.date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="payment_method" class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
              <select id="payment_method" v-model="form.payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="virement">Virement bancaire</option>
                <option value="cheque">Chèque</option>
                <option value="especes">Espèces</option>
              </select>
            </div>
          </div>
        </form>
      </template>
      <template #footer>
        <button @click="$emit('close')" class="btn-secondary mr-2">Annuler</button>
        <button @click="submitForm" class="btn-primary">Effectuer le versement</button>
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

  const emit = defineEmits(['close', 'pay']);

  const form = ref({
    amount: 0,
    date: new Date().toISOString().substr(0, 10),
    payment_method: 'virement',
    type: 'versement'
  });

  const submitForm = () => {
    emit('pay', { ...form.value, landlord_id: props.landlord.id });
  };
  </script>
