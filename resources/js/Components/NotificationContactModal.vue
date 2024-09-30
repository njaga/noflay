<template>
    <div class="fixed inset-0 z-50 overflow-y-auto bg-indigo bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 ease-out scale-95 hover:scale-100">
        <div class="p-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-extrabold text-center w-full text-gray-800">
              {{ title }}
            </h2>
            <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 transform hover:scale-110">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="flex flex-col items-center mb-4">
            <div class="mb-4">
              <div v-if="type === 'success'" class="bg-green-100 rounded-full p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div v-else-if="type === 'error'" class="bg-red-100 rounded-full p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
              <div v-else class="bg-blue-100 rounded-full p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <p class="text-gray-700 text-center">{{ message }}</p>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>


  defineProps({
    title: String,
    message: String,
    type: {
      type: String,
      default: 'info',
      validator: (value) => ['success', 'error', 'info'].includes(value)
    }
  });
  </script>

  <style scoped>
  @keyframes bounce {
    0%, 100% { transform: translateY(-25%); animation-timing-function: cubic-bezier(0.8, 0, 1, 1); }
    50% { transform: translateY(0); animation-timing-function: cubic-bezier(0, 0, 0.2, 1); }
  }
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
  }
  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
  .animate-bounce { animation: bounce 1s infinite; }
  .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
  .animate-spin { animation: spin 1s linear infinite; }
  </style>
