<template>
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 ease-out scale-95 hover:scale-100">
        <div class="p-8">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-extrabold text-indigo-800 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600">
              {{ title }}
            </h2>
            <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 transform hover:scale-110">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          <p class="text-gray-700">{{ message }}</p>
          <div class="mt-4">
            <button @click="$emit('close')"
              class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Fermer
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  defineProps({
    title: String,
    message: String,
  });
  </script>

  <style scoped>
  /* Ajoutez vos styles personnalisés ici */
  </style>
