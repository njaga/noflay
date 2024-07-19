<template>
    <div class="rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl" :class="color">
      <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ title }}</h4>
      <p class="text-3xl font-bold" :class="textColor">
        {{ formattedValue }}
      </p>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';

  const props = defineProps({
    title: {
      type: String,
      required: true
    },
    value: {
      type: Number,
      required: true
    },
    color: {
      type: String,
      default: 'bg-blue-100'
    },
    textColor: {
      type: String,
      default: 'text-blue-600'
    }
  });

  const formattedValue = computed(() => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(props.value);
  });
  </script>
