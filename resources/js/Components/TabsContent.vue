<template>
    <div v-show="isSelected">
      <slot></slot>
    </div>
  </template>

  <script setup>
  import { inject, onMounted, computed } from 'vue';

  const props = defineProps({
    label: {
      type: String,
      required: true
    },
    name: {
      type: String,
      required: true
    }
  });

  const { addTab, selectedTab } = inject('tabs-component');

  onMounted(() => {
    addTab({ label: props.label, name: props.name });
  });

  const isSelected = computed(() => {
    return selectedTab === props.name;
  });
  </script>
