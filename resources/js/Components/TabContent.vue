<template>
    <div>
      <slot :name="activeTab"></slot>
    </div>
  </template>

  <script setup>


  const props = defineProps({
    activeTab: String
  });
  </script>
