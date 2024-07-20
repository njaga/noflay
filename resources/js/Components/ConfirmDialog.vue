<template>
    <Modal :show="true" @close="$emit('cancel')">
      <template #title>
        Confirmation
      </template>
      <template #content>
        <p class="text-sm text-gray-500">
          {{ message }}
        </p>
      </template>
      <template #footer>
        <button @click="$emit('cancel')" class="btn-secondary mr-2">Annuler</button>
        <button @click="$emit('confirm')" class="btn-danger">Confirmer</button>
      </template>
    </Modal>
  </template>

  <script setup>
  import Modal from '@/Components/Modal.vue';

  defineProps({
    message: {
      type: String,
      required: true
    }
  });

  defineEmits(['cancel', 'confirm']);
  </script>
