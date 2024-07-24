<template>
    <Modal :show="show" @close="close">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
        <p class="text-sm text-gray-500 mb-4">
          Êtes-vous sûr de vouloir supprimer le bien "{{ property?.name }}" ? Cette action est irréversible.
        </p>
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="close" class="btn-secondary">Annuler</button>
          <button @click="confirmDelete" class="btn-danger">Supprimer</button>
        </div>
      </div>
    </Modal>
  </template>

  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import Modal from '@/Components/Modal.vue';

  const props = defineProps({
    show: Boolean,
    property: Object
  });

  const emit = defineEmits(['close', 'confirmDelete']);

  const close = () => {
    emit('close');
  };

  const confirmDelete = () => {
    emit('confirmDelete');
  };
  </script>

  <style scoped>
  .btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150;
  }

  .btn-danger {
    @apply inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition ease-in-out duration-150;
  }
  </style>
