<template>
    <Modal :show="show" @close="close">
      <template #title>
        Confirmer la Suppression
      </template>
      <template #content>
        <p>{{ message }}</p>
      </template>
      <template #footer>
        <button @click="confirm" class="btn-danger">Supprimer</button>
        <button @click="close" class="btn-secondary">Annuler</button>
      </template>
    </Modal>
  </template>

  <script setup>
  import Modal from './Modal.vue';

  const props = defineProps({
    show: Boolean,
    message: String,
  });

  const emit = defineEmits(['close', 'confirm']);

  function close() {
    emit('close');
  }

  function confirm() {
    emit('confirm');
  }
  </script>

  <style scoped>
  .btn-danger {
    @apply inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 ease-in-out transform hover:-translate-y-1;
  }

  .btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 ease-in-out transform hover:-translate-y-1;
  }
  </style>
