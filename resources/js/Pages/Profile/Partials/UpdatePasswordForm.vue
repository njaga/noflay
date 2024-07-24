<template>
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-3xl mx-auto">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Mise à jour du mot de passe</h2>
      <form @submit.prevent="updatePassword" class="space-y-6">
        <div class="relative">
          <input
            :type="showCurrentPassword ? 'text' : 'password'"
            v-model="form.current_password"
            placeholder="Mot de passe actuel"
            class="w-full px-4 py-3 rounded-md bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-300"
            :class="{ 'border-red-500': form.errors.current_password }"
          >
          <button
            @click="showCurrentPassword = !showCurrentPassword"
            type="button"
            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
          >
            <Eye v-if="!showCurrentPassword" class="h-5 w-5" />
            <EyeOff v-else class="h-5 w-5" />
          </button>
        </div>
        <div class="relative">
          <input
            :type="showNewPassword ? 'text' : 'password'"
            v-model="form.password"
            placeholder="Nouveau mot de passe"
            class="w-full px-4 py-3 rounded-md bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-300"
            :class="{ 'border-red-500': form.errors.password }"
          >
          <button
            @click="showNewPassword = !showNewPassword"
            type="button"
            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
          >
            <Eye v-if="!showNewPassword" class="h-5 w-5" />
            <EyeOff v-else class="h-5 w-5" />
          </button>
        </div>
        <div class="relative">
          <input
            :type="showConfirmPassword ? 'text' : 'password'"
            v-model="form.password_confirmation"
            placeholder="Confirmer le nouveau mot de passe"
            class="w-full px-4 py-3 rounded-md bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-300"
          >
          <button
            @click="showConfirmPassword = !showConfirmPassword"
            type="button"
            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
          >
            <Eye v-if="!showConfirmPassword" class="h-5 w-5" />
            <EyeOff v-else class="h-5 w-5" />
          </button>
        </div>
        <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Erreur!</strong>
          <ul class="mt-2 list-disc list-inside">
            <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
          </ul>
        </div>
        <button
          type="submit"
          class="w-full bg-indigo-600 text-white font-semibold py-3 px-4 rounded-md hover:bg-indigo-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Mise à jour...' : 'Mettre à jour le mot de passe' }}
        </button>
      </form>
      <transition name="fade">
        <div v-if="form.recentlySuccessful" class="mt-4 bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Succès!</strong>
          <span class="block sm:inline"> Votre mot de passe a été mis à jour avec succès.</span>
        </div>
      </transition>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { Eye, EyeOff } from 'lucide-vue-next';

  const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
  });

  const showCurrentPassword = ref(false);
  const showNewPassword = ref(false);
  const showConfirmPassword = ref(false);

  const updatePassword = () => {
    form.put(route('user-password.update'), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        showCurrentPassword.value = false;
        showNewPassword.value = false;
        showConfirmPassword.value = false;
      },
    });
  };
  </script>

  <style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  </style>
