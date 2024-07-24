<template>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Nom du bien
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Type
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Adresse
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Prix
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Statut
            </th>
            <th scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="property in properties" :key="property.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ property.name }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-500">{{ property.property_type }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-500">{{ property.address }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-indigo-600">{{ formatPrice(property.price) }} CFA</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="[
                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                property.available_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
              ]">
                {{ property.available_count > 0 ? 'Disponible' : 'Indisponible' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button @click="editProperty(property)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                <i class="fas fa-edit"></i>
              </button>
              <button @click="viewPropertyDetails(property)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                <i class="fas fa-eye"></i>
              </button>
              <button @click="togglePropertyStatus(property)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                <i :class="['fas', property.available_count > 0 ? 'fa-toggle-on' : 'fa-toggle-off']"></i>
              </button>
              <button @click="openDeleteModal(property)" class="text-red-600 hover:text-red-900">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import { router } from '@inertiajs/vue3'; // Utilisation directe de router

  const props = defineProps({
    properties: Array,
    auth: Object
  });

  const emit = defineEmits(['openDeleteModal', 'togglePropertyStatus']);

  const viewPropertyDetails = (property) => {
    router.get(route('properties.show', property.id));
  };

  const editProperty = (property) => {
    router.get(route('properties.edit', property.id));
  };

  const openDeleteModal = (property) => {
    emit('openDeleteModal', property);
  };

  const togglePropertyStatus = (property) => {
    emit('togglePropertyStatus', property);
  };

  const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR').format(price);
  };
  </script>
