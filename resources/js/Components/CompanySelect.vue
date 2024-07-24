<template>
    <div>
      <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
      <select
        :id="id"
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        :required="required"
      >
        <option value="">Sélectionnez une entreprise</option>
        <option v-for="company in companies" :key="company.id" :value="company.id">
          {{ company.name }}
        </option>
      </select>
      <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
    </div>
  </template>

  <script setup>
  import { defineProps  } from 'vue';

  const props = defineProps({
    modelValue: [Number, String],
    label: {
      type: String,
      default: 'Entreprise'
    },
    companies: {
      type: Array,
      required: true
    },
    id: {
      type: String,
      default: () => `company-select-${Math.random().toString(36).substr(2, 9)}`
    },
    required: Boolean,
    error: String
  });

   (['update:modelValue']);
  </script>
