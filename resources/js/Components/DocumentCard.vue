<template>
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <i :class="['text-3xl text-gray-400', icon]"></i>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dt class="text-sm font-medium text-gray-500 truncate">
              {{ title }}
            </dt>
            <dd class="flex items-center text-sm text-gray-900">
              {{ document ? document.name : 'Aucun document' }}
            </dd>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 px-5 py-3">
        <div class="text-sm">
          <input type="file" ref="fileInput" class="hidden" @change="handleFileUpload" />
          <button v-if="!document" @click="$refs.fileInput.click()" class="font-medium text-indigo-600 hover:text-indigo-500">
            Ajouter un document
          </button>
          <div v-else class="flex justify-between">
            <button @click="$emit('download', document)" class="font-medium text-blue-600 hover:text-blue-500">
              Télécharger
            </button>
            <button @click="$emit('delete', document)" class="font-medium text-red-600 hover:text-red-500">
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';

  const props = defineProps({
    title: String,
    icon: String,
    document: Object,
  });

  const emit = defineEmits(['upload', 'download', 'delete']);

  const fileInput = ref(null);

  const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
      emit('upload', file);
      event.target.value = ''; // Reset input
    }
  };
  </script>
