<template>
    <Modal :show="true" @close="$emit('close')">
      <template #title>
        Modifier les informations du bailleur
      </template>
      <template #content>
        <form @submit.prevent="submitForm">
          <div class="grid grid-cols-1 gap-6">
            <div>
              <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
              <input type="text" id="first_name" v-model="form.first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
              <input type="text" id="last_name" v-model="form.last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" id="email" v-model="form.email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
              <input type="tel" id="phone" v-model="form.phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
              <textarea id="address" v-model="form.address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
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

  const emit = defineEmits(['close', 'update']);

  const form = ref({
    first_name: props.landlord.first_name,
    last_name: props.landlord.last_name,
    email: props.landlord.email,
    phone: props.landlord.phone,
    address: props.landlord.address
  });

  const submitForm = () => {
    emit('update', form.value);
  };
  </script>
