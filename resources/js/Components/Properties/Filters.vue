<template>
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div>
          <label for="filter-date" class="block text-sm font-medium text-gray-700">Date d'ajout</label>
          <input type="date" id="filter-date" v-model="filters.date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
        </div>
        <div>
          <label for="filter-owner" class="block text-sm font-medium text-gray-700">Bailleur</label>
          <select id="filter-owner" v-model="filters.owner" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="">Tous les bailleurs</option>
            <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
              {{ landlord.first_name }} {{ landlord.last_name }}
            </option>
          </select>
        </div>
        <div>
          <label for="filter-type" class="block text-sm font-medium text-gray-700">Type de bien</label>
          <select id="filter-type" v-model="filters.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="">Tous les types</option>
            <option v-for="type in propertyTypes" :key="type">{{ type }}</option>
          </select>
        </div>
        <div>
          <label for="filter-availability" class="block text-sm font-medium text-gray-700">Disponibilité</label>
          <select id="filter-availability" v-model="filters.availability" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="all">Toutes</option>
            <option value="available">Disponible</option>
            <option value="unavailable">Indisponible</option>
          </select>
        </div>
      </div>
      <div class="mt-4 flex justify-end space-x-3">
        <button @click="applyFilters" class="btn-primary">Appliquer les filtres</button>
        <button @click="resetFilters" class="btn-secondary">Réinitialiser</button>
      </div>
    </div>
  </template>

  <script setup>
  import { defineProps, defineEmits } from 'vue';

  const props = defineProps({
    landlords: Array,
    propertyTypes: Array,
    filters: Object,
  });

  const emit = defineEmits(['applyFilters', 'resetFilters']);

  const applyFilters = () => {
    emit('applyFilters');
  };

  const resetFilters = () => {
    emit('resetFilters');
  };
  </script>

  <style scoped>
  .btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
  }

  .btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150;
  }
  </style>
