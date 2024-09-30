<template>
    <div class="bg-white shadow-md rounded-lg mt-4 p-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <span class="text-sm font-medium text-gray-500 mr-2">Statut:</span>
          <span :class="[
            'px-2 py-1 text-xs font-bold rounded',
            status === 'Actif' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-gray-800',
          ]">
            {{ status }}
          </span>
        </div>
        <div class="flex space-x-2">
          <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
            <i class="bi bi-filetype-pdf"></i>
            Générer Contrat
          </button>
          <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-150 ease-in-out">
            <i class="bi bi-filetype-pdf"></i>
            Générer Reçu
          </button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  defineProps({
    status: {
      type: String,
      required: true,
    },
  });
  </script>
