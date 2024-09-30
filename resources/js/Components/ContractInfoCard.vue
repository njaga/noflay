<template>
    <div class="bg-white shadow-lg rounded-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
      <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4 flex items-center justify-between">
        <div class="flex items-center">
          <i :class="['text-white text-2xl mr-3', icon]"></i>
          <h3 class="text-xl font-semibold text-white">{{ title }}</h3>
        </div>
        <span v-if="status" :class="[
          'px-2 py-1 text-xs font-bold rounded',
          status === 'Actif' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-gray-800'
        ]">
          {{ status }}
        </span>
      </div>
      <div class="p-6">
        <ul class="space-y-4">
          <li v-for="item in items" :key="item.label" class="flex items-center justify-between">
            <div class="flex items-center">
              <i :class="['text-indigo-600 mr-3', item.icon]"></i>
              <span class="text-gray-700 font-medium">{{ item.label }}</span>
            </div>
            <span :class="[
              'ml-2 text-gray-900 font-semibold',
              item.highlight ? 'text-indigo-600' : ''
            ]">{{ item.value }}</span>
          </li>
        </ul>
      </div>
      <div v-if="footer" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <p class="text-sm text-gray-600">{{ footer }}</p>
      </div>
    </div>
  </template>

  <script setup>


  const props = defineProps({
    title: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      required: true
    },
    items: {
      type: Array,
      required: true
    },
    status: {
      type: String,
      default: null
    },
    footer: {
      type: String,
      default: null
    }
  });
  </script>

  <style scoped>
  @import "bootstrap-icons/font/bootstrap-icons.css";
  </style>
