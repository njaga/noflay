<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';


const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-3xl mx-auto">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Supprimer le compte</h2>

      <p class="text-sm text-gray-600 mb-4">
        Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.
      </p>

      <button
        @click="confirmUserDeletion"
        class="w-full bg-red-600 text-white font-semibold py-3 px-4 rounded-md hover:bg-red-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
      >
        Supprimer le compte
      </button>

      <Modal :show="confirmingUserDeletion" @close="closeModal">
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Êtes-vous sûr de vouloir supprimer votre compte ?</h3>
          <p class="text-sm text-gray-600 mb-4">
            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.
          </p>
          <div class="mt-4">
            <input
              ref="passwordInput"
              v-model="form.password"
              type="password"
              class="w-full px-4 py-3 rounded-md bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition duration-300"
              placeholder="Mot de passe"
              @keyup.enter="deleteUser"
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
              @click="deleteUser"
              class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Supprimer le compte
            </button>
          </div>
        </div>
      </Modal>
    </div>
  </template>

