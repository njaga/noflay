<template>
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 ease-out scale-95 hover:scale-100">
        <div class="p-8">
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-extrabold text-indigo-800 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600">
              <i class="bi bi-calendar-check mr-2"></i>Demander une démo de Noflay
            </h2>
            <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 transform hover:scale-110">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>

          <form v-if="!showResult" @submit.prevent="submitDemoRequest" class="space-y-6">
            <div class="relative group">
              <input
                type="text"
                id="demo-name"
                v-model="demoRequest.name"
                class="peer w-full border-0 border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 bg-transparent transition-all duration-300 pt-6 pb-2"
                placeholder="Nom complet"
                required
              />
              <label
                for="demo-name"
                class="absolute left-0 top-2 text-gray-600 text-sm transition-all duration-300
                       peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                       peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:text-indigo-600
                       peer-focus:text-sm"
              >
                <i class="bi bi-person mr-2"></i>Nom complet
              </label>
            </div>

            <div class="relative group">
              <input
                type="email"
                id="demo-email"
                v-model="demoRequest.email"
                class="peer w-full border-0 border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 bg-transparent transition-all duration-300 pt-6 pb-2"
                placeholder="Email"
                required
              />
              <label
                for="demo-email"
                class="absolute left-0 top-2 text-gray-600 text-sm transition-all duration-300
                       peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                       peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:text-indigo-600
                       peer-focus:text-sm"
              >
                <i class="bi bi-envelope mr-2"></i>Email
              </label>
            </div>

            <div class="relative group">
              <input
                type="tel"
                id="demo-phone"
                v-model="demoRequest.phone"
                class="peer w-full border-0 border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 bg-transparent transition-all duration-300 pt-6 pb-2"
                placeholder="Téléphone"
                required
              />
              <label
                for="demo-phone"
                class="absolute left-0 top-2 text-gray-600 text-sm transition-all duration-300
                       peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                       peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:text-indigo-600
                       peer-focus:text-sm"
              >
                <i class="bi bi-telephone mr-2"></i>Téléphone
              </label>
            </div>

            <div class="relative group">
              <input
                type="text"
                id="demo-company"
                v-model="demoRequest.company"
                class="peer w-full border-0 border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 bg-transparent transition-all duration-300 pt-6 pb-2"
                placeholder="Entreprise"
                required
              />
              <label
                for="demo-company"
                class="absolute left-0 top-2 text-gray-600 text-sm transition-all duration-300
                       peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                       peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:text-indigo-600
                       peer-focus:text-sm"
              >
                <i class="bi bi-building mr-2"></i>Entreprise
              </label>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full py-3 px-4
                     hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2
                     focus:ring-indigo-500 focus:ring-offset-2 transform transition-all duration-200
                     hover:scale-105 font-semibold text-lg disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i v-if="!isLoading" class="bi bi-send mr-2"></i>
              <i v-else class="bi bi-arrow-repeat animate-spin mr-2"></i>
              {{ isLoading ? 'Envoi en cours...' : 'Envoyer la demande' }}
            </button>
          </form>

          <div v-if="showResult" :class="['p-4 rounded-lg mt-4', isSuccess ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
            <p class="text-center font-semibold">{{ resultMessage }}</p>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, reactive, onMounted } from 'vue';
  import axios from 'axios';

  const emit = defineEmits(['close', 'request-sent']);

  const demoRequest = reactive({
    name: '',
    email: '',
    phone: '',
    company: ''
  });

  const isLoading = ref(false);
  const showResult = ref(false);
  const resultMessage = ref('');
  const isSuccess = ref(false);
  const csrfToken = ref('');

  onMounted(async () => {
    try {
      await axios.get('/sanctum/csrf-cookie');
      csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    } catch (error) {
      console.error('Failed to fetch CSRF token:', error);
    }
  });

  const submitDemoRequest = async () => {
    isLoading.value = true;
    showResult.value = false;

    try {
      const response = await axios.post('/demo-request', demoRequest, {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken.value
        },
        withCredentials: true
      });

      if (response.status === 200) {
        isSuccess.value = true;
        resultMessage.value = 'Votre demande de démo a été envoyée avec succès !';
        emit('request-sent', 'success');
      } else {
        throw new Error('Erreur lors de l\'envoi de la demande');
      }
    } catch (error) {
      isSuccess.value = false;
      resultMessage.value = 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.';
      emit('request-sent', 'error');
      console.error('Error submitting demo request:', error);
      if (error.response) {
        console.error('Error data:', error.response.data);
        console.error('Error status:', error.response.status);
        console.error('Error headers:', error.response.headers);
      } else if (error.request) {
        console.error('Error request:', error.request);
      } else {
        console.error('Error message:', error.message);
      }
      console.error('Error config:', error.config);
    } finally {
      isLoading.value = false;
      showResult.value = true;
      if (isSuccess.value) {
        setTimeout(() => {
          emit('close');
        }, 3000);
      }
    }
  };
  </script>

  <style scoped>
  @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css");

  /* Add any additional styles here if needed */
  </style>
