<template>
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:scale-102 hover:shadow-3xl">
      <div class="px-8 py-6 bg-gradient-to-r from-purple-600 to-indigo-600">
        <h2 class="text-2xl font-extrabold text-white flex items-center">
          <i :class="[icon, 'mr-4 text-3xl']"></i>
          {{ title }}
        </h2>
      </div>
      <div class="p-8">
        <TransitionGroup
          name="list"
          tag="div"
          class="space-y-6"
          v-if="items && items.length > 0"
        >
          <slot
            name="item"
            v-for="item in items"
            :key="item.id"
            :item="item"
          ></slot>
        </TransitionGroup>
        <p v-else class="text-gray-600 bg-gray-100 p-6 rounded-2xl text-center animate-pulse">
          Aucun élément associé.
        </p>
      </div>
    </div>
  </template>

  <script setup>
  import { defineProps } from 'vue'

  const props = defineProps({
    title: String,
    icon: String,
    items: Array,
  })
  </script>

  <style scoped>
  .list-enter-active,
  .list-leave-active {
    transition: all 0.5s ease;
  }
  .list-enter-from,
  .list-leave-to {
    opacity: 0;
    transform: translateX(30px);
  }
  .hover\:scale-102:hover {
    transform: scale(1.02);
  }
  .hover\:shadow-3xl:hover {
    box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
  }
  </style>
