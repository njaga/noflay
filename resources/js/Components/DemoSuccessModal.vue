<template>
    <transition name="fade">
      <div v-if="isVisible" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 ease-out scale-95 hover:scale-100">
          <div class="p-8">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-2xl font-extrabold text-indigo-800 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600">
                <i :class="statusIcon" class="mr-2"></i>{{ title }}
              </h2>
              <button @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 transform hover:scale-110">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
            <p class="text-gray-700">{{ message }}</p>
            <div class="mt-6 text-right">
              <button @click="closeModal" class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:scale-105">
                Fermer
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </template>

  <script setup>
  import { ref, watch, computed } from 'vue';

  const props = defineProps({
    title: String,
    message: String,
    type: {
      type: String,
      default: 'success', // 'success' or 'error'
    },
    visible: Boolean,
  });

  const emit = defineEmits(['close']);

  const isVisible = ref(props.visible);

  const statusIcon = computed(() =>
    props.type === 'error' ? 'bi bi-exclamation-circle' : 'bi bi-check-circle'
  );

  const closeModal = () => {
    isVisible.value = false;
    emit('close');
  };

  watch(() => props.visible, (newVal) => {
    isVisible.value = newVal;
    if (newVal) {
      setTimeout(() => {
        closeModal();
      }, 5000);
    }
  });
  </script>

  <style scoped>
  @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css");

  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }
  </style>
