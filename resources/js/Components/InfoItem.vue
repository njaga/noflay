<template>
    <div class="flex items-center space-x-2 mb-2">
      <component :is="icon" class="w-5 h-5 text-gray-500" />
      <span class="font-semibold">{{ label }}:</span>
      <span>{{ value }}</span>
    </div>
  </template>

  <script setup>
  import { defineProps } from 'vue';

  defineProps({
    icon: Object,
    label: String,
    value: [String, Number],
  });
  </script>
