<template>
    <div>
      <slot :name="activeTab"></slot>
    </div>
  </template>

  <script setup>
  import { defineProps } from 'vue';

  const props = defineProps({
    activeTab: String
  });
  </script>
