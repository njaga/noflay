<template>
    <span :class="['px-3 py-1 rounded-full text-sm font-medium flex items-center', statusConfig.classes]">
      <i :class="[statusConfig.icon, 'mr-2']"></i>
      {{ status }}
    </span>
  </template>

  <script setup>
  import { computed } from 'vue';

  const props = defineProps({
    status: {
      type: String,
      required: true
    }
  });

  const statusConfig = computed(() => {
    const configs = {
      'Completed': { classes: 'bg-green-500 text-white', icon: 'bi-check-circle' },
      'Pending': { classes: 'bg-yellow-500 text-white', icon: 'bi-clock' },
      'Failed': { classes: 'bg-red-500 text-white', icon: 'bi-x-circle' }
    };
    return configs[props.status] || { classes: 'bg-gray-500 text-white', icon: 'bi-question-circle' };
  });
  </script>
