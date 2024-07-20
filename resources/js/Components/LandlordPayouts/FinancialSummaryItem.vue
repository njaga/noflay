<template>
    <div class="px-4 py-5 sm:p-6">
      <dt class="text-sm font-medium text-gray-500 truncate">
        {{ label }}
      </dt>
      <dd :class="['mt-1 text-3xl font-semibold', textColor]">
        {{ formattedAmount }}
      </dd>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';

  const props = defineProps({
    label: String,
    amount: Number,
    textColor: String,
    negative: Boolean,
  });

  const formattedAmount = computed(() => {
    const prefix = props.negative ? '- ' : '';
    return prefix + new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'EUR',
      minimumFractionDigits: 2,
    }).format(Math.abs(props.amount));
  });
  </script>
