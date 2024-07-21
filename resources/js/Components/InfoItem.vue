<template>
    <div class="flex items-center">
      <i :class="[icon, 'text-indigo-500 mr-2']"></i>
      <div>
        <span class="text-sm text-gray-500">{{ label }}</span>
        <p class="font-medium">{{ value }}</p>
      </div>
    </div>
  </template>

  <script setup>
  defineProps({
    icon: String,
    label: String,
    value: [String, Number]
  });
  </script>
