<template>
    <div>
      <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        :required="required"
      >
      <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
    </div>
  </template>

  <script setup>
  import { defineProps  } from 'vue';

  const props = defineProps({
    modelValue: String,
    label: String,
    type: {
      type: String,
      default: 'text'
    },
    id: {
      type: String,
      default: () => `input-${Math.random().toString(36).substr(2, 9)}`
    },
    required: Boolean,
    error: String
  });

   (['update:modelValue']);
  </script>
