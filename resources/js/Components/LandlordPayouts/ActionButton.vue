<template>
    <component
      :is="tag"
      :href="href"
      :class="[
        'inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2',
        buttonClasses
      ]"
      @click="$emit('click')"
    >
      <heroicon :icon="icon" class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
      <slot></slot>
    </component>
  </template>

  <script setup>
  import { computed } from 'vue';
  import { heroicon } from '@/Components/Icons';

  const props = defineProps({
    href: String,
    icon: String,
    type: {
      type: String,
      default: 'primary'
    }
  });

  const buttonClasses = computed(() => ({
    'primary': 'text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
    'secondary': 'text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:ring-indigo-500',
    'danger': 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500'
  })[props.type]);

  const tag = computed(() => props.href ? 'a' : 'button');
  </script>
