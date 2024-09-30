<template>
    <div class="bg-gray-50 rounded-lg p-4 flex items-center">
      <i :class="['fas', icon, 'text-2xl text-indigo-600 mr-3']"></i>
      <div>
        <h3 class="text-sm font-medium text-gray-500">{{ title }}</h3>
        <p class="mt-1 text-lg font-semibold text-gray-900">{{ value }}</p>
      </div>
    </div>
  </template>

  <script setup>


  const props = defineProps({
    icon: String,
    title: String,
    value: [String, Number]
  });
  </script>
