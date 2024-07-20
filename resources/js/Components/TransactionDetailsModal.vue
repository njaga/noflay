<!-- resources/js/Components/TransactionDetailsModal.vue -->
<template>
    <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="my-modal">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Détails de la Transaction
          </h3>
          <div class="mt-2">
            <p class="text-sm text-gray-500">
              <strong>Date :</strong> {{ formatDate(transaction.date) }}<br>
              <strong>Description :</strong> {{ transaction.description }}<br>
              <strong>Montant :</strong> {{ formatCurrency(transaction.amount) }}<br>
              <strong>Type :</strong> {{ transaction.type }}
            </p>
          </div>
          <div class="items-center px-4 py-3">
            <button @click="close" id="ok-btn" class="px-4 py-2 bg-indigo-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-300">
              Fermer
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';

  const props = defineProps({
    show: Boolean,
    transaction: Object,
  });

  const emit = defineEmits(['close']);

  const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(value);
  };

  const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
  };

  const close = () => {
    emit('close');
  };
  </script>

  <style scoped>
  /* Ajoutez ici les styles pour la modal */
  </style>
