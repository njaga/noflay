<template>
    <Modal :show="show" @close="close">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          <template v-if="type === 'success'">Succès</template>
          <template v-else>Erreur</template>
        </h3>
        <p class="text-sm text-gray-500 mb-4">
          {{ message || `Le statut du bien "${property?.name}" a été mis à jour avec succès.` }}
        </p>
        <div class="mt-6 flex justify-end">
          <button @click="close" class="btn-primary">OK</button>
        </div>
      </div>
    </Modal>
  </template>

  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import Modal from '@/Components/Modal.vue';

  const props = defineProps({
    show: Boolean,
    property: Object,
    message: String,
    type: {
      type: String,
      default: 'success',
    },
  });

  const emit = defineEmits(['close']);

  const close = () => {
    emit('close');
  };
  </script>

  <style scoped>
  .btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
  }
  </style>
