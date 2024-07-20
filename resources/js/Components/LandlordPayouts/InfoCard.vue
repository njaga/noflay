<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          {{ title }}
        </h3>
        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div v-for="item in items" :key="item.label" class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500 flex items-center">
              <heroicon :icon="item.icon" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
              {{ item.label }}
            </dt>
            <dd class="mt-1 text-sm text-gray-900">
              <component v-if="item.component" :is="item.component" v-bind="item" />
              <template v-else>{{ item.value }}</template>
            </dd>
          </div>
        </dl>
      </div>
    </div>
  </template>

  <script setup>
  import { defineProps } from 'vue';
  import { heroicon } from '@/Components/Icons';

  defineProps({
    title: String,
    items: Array,
  });
  </script>
