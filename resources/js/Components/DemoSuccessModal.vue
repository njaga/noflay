<template>
    <transition name="fade">
      <div v-if="isVisible" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 ease-out scale-95 hover:scale-100">
          <div class="p-8">
            <div class="flex justify-between items-center mb-6">
              <h2 :class="titleClass" class="flex items-center">
                <i :class="[statusIcon, 'mr-3 text-3xl animate-bounce']"></i>
                {{ title }}
              </h2>
              <button @click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 transform hover:scale-110">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
            <p class="text-gray-700 dark:text-gray-300 mb-6">{{ message }}</p>
            <div class="text-right">
              <button @click="closeModal" :class="buttonClass">
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
      default: 'success', // 'success', 'error', or 'warning'
    },
    visible: Boolean,
  });

  const emit = defineEmits(['close']);

  const isVisible = ref(props.visible);

  const statusIcon = computed(() => {
    switch (props.type) {
      case 'error':
        return 'bi bi-x-circle text-red-500';
      case 'warning':
        return 'bi bi-exclamation-triangle text-yellow-500';
      default:
        return 'bi bi-check-circle text-green-500';
    }
  });

  const titleClass = computed(() => {
    const baseClass = 'text-2xl font-extrabold bg-clip-text text-transparent';
    switch (props.type) {
      case 'error':
        return `${baseClass} bg-gradient-to-r from-red-500 to-pink-600`;
      case 'warning':
        return `${baseClass} bg-gradient-to-r from-yellow-500 to-orange-600`;
      default:
        return `${baseClass} bg-gradient-to-r from-green-500 to-emerald-600`;
    }
  });

  const buttonClass = computed(() => {
    const baseClass = 'font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2';
    switch (props.type) {
      case 'error':
        return `${baseClass} bg-red-500 hover:bg-red-400 focus:ring-red-500 text-white`;
      case 'warning':
        return `${baseClass} bg-yellow-500 hover:bg-yellow-400 focus:ring-yellow-500 text-white`;
      default:
        return `${baseClass} bg-green-500 hover:bg-green-400 focus:ring-green-500 text-white`;
    }
  });

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
    transition: opacity 0.5s, transform 0.5s;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: scale(0.95);
  }

  @keyframes bounce {
    0%, 100% {
      transform: translateY(-25%);
      animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
      transform: translateY(0);
      animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
  }
  .animate-bounce {
    animation: bounce 1s infinite;
  }
  </style>
