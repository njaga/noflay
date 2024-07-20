<template>
    <form @submit.prevent="$emit('submit', contactForm)" class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
      <div class="sm:col-span-2">
        <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
        <div class="mt-1">
          <input type="text" id="name" v-model="contactForm.name" required
            class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" />
        </div>
      </div>
      <div class="sm:col-span-2">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <div class="mt-1">
          <input type="email" id="email" v-model="contactForm.email" required
            class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" />
        </div>
      </div>
      <div class="sm:col-span-2">
        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
        <div class="mt-1">
          <textarea id="message" v-model="contactForm.message" rows="4" required
            class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"></textarea>
        </div>
      </div>
      <div class="sm:col-span-2">
        <button type="submit"
          class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Envoyer
        </button>
      </div>
    </form>
  </template>

  <script setup>
  import { ref } from 'vue';

  const contactForm = ref({
    name: '',
    email: '',
    message: ''
  });
  </script>
