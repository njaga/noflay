<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import ActionSection from '@/Components/ActionSection.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';

defineProps({
    sessions: Array,
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmLogout = () => {
    confirmingLogout.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;

    form.reset();
};
</script>

<template>
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-3xl mx-auto">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Sessions de navigateur</h2>

      <p class="text-sm text-gray-600 mb-4">
        Gérez et déconnectez-vous de vos sessions actives sur d'autres navigateurs et appareils.
      </p>

      <div v-if="sessions.length > 0" class="mb-6 space-y-4">
        <div v-for="(session, i) in sessions" :key="i" class="flex items-center p-3 bg-gray-100 rounded-md">
          <div class="mr-3">
            <svg v-if="session.agent.is_desktop" class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <svg v-else class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <div class="text-sm font-semibold text-gray-600">
              {{ session.agent.platform ? session.agent.platform : 'Unknown' }} - {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
            </div>
            <div class="text-xs text-gray-500">
              {{ session.ip_address }},
              <span v-if="session.is_current_device" class="text-green-500 font-semibold">Cet appareil</span>
              <span v-else>Dernière activité {{ session.last_active }}</span>
            </div>
          </div>
        </div>
      </div>

      <button
        @click="confirmLogout"
        class="w-full bg-indigo-600 text-white font-semibold py-3 px-4 rounded-md hover:bg-indigo-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
      >
        Se déconnecter des autres appareils
      </button>

      <Modal :show="confirmingLogout" @close="closeModal">
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Déconnexion des autres sessions de navigateur</h3>
          <p class="text-sm text-gray-600 mb-4">
            Veuillez saisir votre mot de passe pour confirmer que vous souhaitez vous déconnecter de vos autres sessions de navigateur sur tous vos appareils.
          </p>
          <div class="mt-4">
            <input
              ref="passwordInput"
              v-model="form.password"
              type="password"
              class="w-full px-4 py-3 rounded-md bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-300"
              placeholder="Mot de passe"
              @keyup.enter="logoutOtherBrowserSessions"
            />
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
          </div>
          <div class="mt-6 flex justify-end space-x-3">
            <button
              @click="closeModal"
              class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
              Annuler
            </button>
            <button
              @click="logoutOtherBrowserSessions"
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Se déconnecter
            </button>
          </div>
        </div>
      </Modal>
    </div>
  </template>

