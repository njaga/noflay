<template>
    <Head title="Vérification de l'e-mail" />
    <div class="min-h-screen flex bg-gray-100 overflow-hidden">
        <div class="w-full flex relative">
            <!-- Panneau de gauche -->
            <div class="w-1/2 transition-all duration-700 ease-in-out bg-indigo-pattern hidden md:block">
                <div class="h-full p-8 flex flex-col justify-center items-center">
                    <img src="" alt="">
                    <h2 class="text-3xl font-bold text-center mb-6 text-white title">
                        Vérification de l'e-mail
                    </h2>
                    <p class="mb-8 text-center text-white description">
                        Confirmez votre adresse e-mail pour sécuriser votre compte
                    </p>
                </div>
            </div>

            <!-- Panneau de droite avec le formulaire -->
            <div class="w-full md:w-1/2 bg-white-pattern">
                <div class="h-full p-8 flex flex-col justify-center items-center">
                    <h3 class="text-2xl font-bold text-center mb-6 text-indigo-600">
                        Vérifiez votre e-mail
                    </h3>
                    <div class="w-full max-w-sm space-y-4">
                        <p class="text-sm text-gray-600 mb-4">
                            Avant de continuer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer. Si vous n'avez pas reçu l'e-mail, nous vous en enverrons volontiers un autre.
                        </p>

                        <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                            Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie dans vos paramètres de profil.
                        </div>

                        <form @submit.prevent="submit">
                            <div class="flex flex-col space-y-4">
                                <button
                                    type="submit"
                                    class="w-full bg-indigo-500 text-white px-4 py-2 rounded-full hover:bg-indigo-600 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Envoi en cours...
                                    </span>
                                    <span v-else>
                                        Renvoyer l'e-mail de vérification
                                    </span>
                                </button>

                                <div class="flex justify-between text-sm">
                                    <Link
                                        :href="route('profile.show')"
                                        class="text-indigo-600 hover:text-indigo-800 transition duration-300"
                                    >
                                        Modifier le profil
                                    </Link>

                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="text-indigo-600 hover:text-indigo-800 transition duration-300"
                                    >
                                        Se déconnecter
                                    </Link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <Transition name="modal">
        <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    E-mail envoyé
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Un nouvel e-mail de vérification a été envoyé à votre adresse e-mail.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="closeModal"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
});

const showModal = ref(false);

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = true;
        },
    });
};

const closeModal = () => {
    showModal.value = false;
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<style scoped>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";

/* Modal transition */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

/* Mobile slide animation */
@media (max-width: 768px) {
    .form-container {
        transition: transform 0.7s ease-in-out;
    }

    .form-container.slide-left {
        transform: translateX(-100%);
    }

    .form-container.slide-right {
        transform: translateX(0);
    }
}

/* Custom styles */
.rounded-full {
    border-radius: 9999px;
}

input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

/* Background patterns */
.bg-indigo-pattern {
    background-color: #6200ea;
    opacity: 1;
    background-image: radial-gradient(circle at center center, #e5e8ff, #e8d8ff),
        repeating-radial-gradient(
            circle at center center,
            #e5e8ff,
            #e5e8ff,
            6px,
            transparent 12px,
            transparent 6px
        );
    background-blend-mode: multiply;
}

.bg-white-pattern {
    background-color: rgb(255, 255, 255);
    background-image: radial-gradient(
            circle at center center,
            #e5e8ff,
            transparent
        ),
        repeating-radial-gradient(
            circle at center center,
            #fafbff,
            #ffffff,
            5px,
            transparent 12px,
            transparent 6px
        );
    background-blend-mode: multiply;
}
</style>
