<template>
    <Modal :show="show" @close="handleClose">
        <div class="p-8 bg-white rounded-lg shadow-xl">
            <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="bi bi-person-plus me-2"></i>
                Créer un compte pour le locataire
            </h3>
            <p v-if="!accountCreated && !errorMessage" class="text-sm text-gray-600 mb-6">
                Pour : {{ tenant?.first_name }} {{ tenant?.last_name }}<br>
                Email : {{ tenant?.email }}
            </p>

            <TransitionGroup name="fade" tag="div">
                <div v-if="errorMessage" key="error" class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg flex items-center">
                    <i class="bi bi-exclamation-circle-fill text-2xl me-2 animate-bounce"></i>
                    {{ errorMessage }}
                </div>

                <div v-if="accountCreated" key="success" class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg flex items-start">
                    <i class="bi bi-check-circle-fill text-2xl me-2 animate-bounce"></i>
                    <div>
                        <p class="font-semibold">Compte créé avec succès!</p>
                        <p>Email: {{ accountDetails.email }}</p>
                        <p>Mot de passe: {{ accountDetails.password }}</p>
                    </div>
                </div>
            </TransitionGroup>

            <div class="mt-8 flex justify-end space-x-3">
                <button @click="handleClose" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out flex items-center">
                    <i class="bi bi-x-circle me-2"></i>
                    Fermer
                </button>
                <button
                    @click="createAccount"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out flex items-center"
                    :class="{ 'opacity-50 cursor-not-allowed': accountCreated || isLoading }"
                    :disabled="accountCreated || isLoading"
                >
                    <i v-if="!isLoading" class="bi bi-person-plus-fill me-2"></i>
                    <div v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    {{ isLoading ? 'Création...' : 'Créer un compte' }}
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: Boolean,
    tenant: Object,
});

const emit =  defineEmits(['close']);
const accountCreated = ref(false);
const accountDetails = ref({});
const errorMessage = ref('');
const isLoading = ref(false);

const handleClose = () => {
    accountCreated.value = false;
    accountDetails.value = {};
    errorMessage.value = '';
    emit('close');
};

const createAccount = async () => {
    if (isLoading.value) return;

    isLoading.value = true;
    errorMessage.value = '';

    try {
        const response = await axios.post(route('tenants.create-account', { id: props.tenant.id }));
        accountDetails.value = response.data;
        accountCreated.value = true;
    } catch (error) {
        if (error.response && error.response.data && error.response.data.error) {
            errorMessage.value = error.response.data.error;
        } else {
            errorMessage.value = 'Echec lors de la création du compte';
        }
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
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
