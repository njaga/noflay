<!-- Components/LandlordPayouts/DeletePayoutModal.vue -->
<template>
    <Modal :show="show" @close="$emit('close')">
      <div class="p-6 sm:p-10">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
          <i class="bi bi-exclamation-triangle-fill text-red-500 mr-3"></i>
          Confirmer la suppression
        </h3>
        <p class="text-gray-600 mb-6">
          Êtes-vous sûr de vouloir supprimer ce versement ? Cette action est irréversible.
        </p>
        <div class="flex justify-end space-x-4">
          <button @click="$emit('close')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center">
            <i class="bi bi-x-lg mr-2"></i>
            Annuler
          </button>
          <button @click="$emit('confirm')" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center">
            <i class="bi bi-trash-fill mr-2"></i>
            Supprimer
          </button>
        </div>
      </div>
    </Modal>
  </template>

  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import Modal from "@/Components/Modal.vue";

  const props = defineProps({
    show: {
      type: Boolean,
      required: true
    }
  });

  defineEmits(['close', 'confirm']);
  </script>
