<template>
    <section id="contact"
        class="bg-gradient-to-br bg-indigo-50 text-gray-800 py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <p
                    class="mt-2 text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-900 animate-slide-up">
                    Contacter notre support
                </p>
                <p class="mt-4 text-xl text-gray-600 animate-fade-in">La solution pratique pour une gestion immobilière efficace et sans stress.</p>
            </div>

            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div
                        class="p-10 bg-gradient-to-br from-indigo-600 to-indigo-900 text-white flex flex-col justify-between">
                        <div>
                            <h3 class="text-3xl font-bold mb-6">Restons connectés</h3>
                            <p class="mb-10 text-lg">Remplissez le formulaire et notre équipe
                                vous répondra dans les 24 heures.</p>
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-indigo-500 rounded-full flex items-center justify-center">
                                        <i class="bi bi-geo-alt-fill text-xl"></i>
                                    </div>
                                    <div class="ml-4 text-lg">Scat Urbam, Dakar, Sénégal</div>
                                </div>
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-indigo-500 rounded-full flex items-center justify-center">
                                        <i class="bi bi-telephone-fill text-xl"></i>
                                    </div>
                                    <div class="ml-4 text-lg">+221 78 163 34 19</div>
                                </div>
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-indigo-500 rounded-full flex items-center justify-center">
                                        <i class="bi bi-envelope-fill text-xl"></i>
                                    </div>
                                    <div class="ml-4 text-lg">contact@noflay.com</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-10">
                            <h4 class="text-2xl font-bold mb-4">Suivez-nous</h4>
                            <div class="flex space-x-4">
                                <a v-for="social in socialLinks" :key="social.icon" :href="social.url"
                                    class="text-white hover:text-pink-300 transition-colors duration-300">
                                    <i :class="['bi', social.icon, 'text-3xl']"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-10 lg:p-16">
                        <h3 class="text-2xl font-bold mb-6 text-gray-800">Envoyez-nous un message</h3>
                        <form @submit.prevent="submitContactForm">
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom
                                        complet</label>
                                    <input type="text" id="name" v-model="form.name" required
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300" />
                                </div>
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" id="email" v-model="form.email" required
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300" />
                                </div>
                                <div>
                                    <label for="message"
                                        class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                    <textarea id="message" v-model="form.message" rows="4" required
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300"></textarea>
                                </div>
                                <div>
                                    <button type="submit" :disabled="isLoading"
                                        class="w-full flex justify-center items-center px-6 py-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-gradient-to-r from-indigo-900 to-purple-600 hover:from-indigo-900 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="mr-2">{{ isLoading ? 'Envoi en cours...' : 'Envoyer le message'
                                            }}</span>
                                        <i v-if="!isLoading" class="bi bi-send-fill"></i>
                                        <i v-else class="bi bi-hourglass-split animate-spin"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <NotificationContactModal v-if="notification.show" :title="notification.title" :message="notification.message"
            @close="notification.show = false" />
    </section>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import NotificationContactModal from '@/Components/NotificationContactModal.vue';

const form = ref({
    name: '',
    email: '',
    message: '',
});

const notification = ref({
    show: false,
    title: '',
    message: '',
});

const isLoading = ref(false);

const socialLinks = [
    { icon: 'bi-facebook', url: 'https://facebook.com/Noflay' },
    { icon: 'bi-twitter', url: 'https://twitter.com/Noflay' },
    { icon: 'bi-instagram', url: 'https://instagram.com/Noflay' },
    { icon: 'bi-linkedin', url: 'https://linkedin.com/company/Noflay' }
];

const submitContactForm = async () => {
    isLoading.value = true;
    try {
        const response = await axios.post('/contact', form.value);
        if (response.data.success) {
            notification.value = {
                show: true,
                title: 'Message envoyé',
                message: 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
            };
            form.value = { name: '', email: '', message: '' };
        } else {
            throw new Error('Erreur serveur');
        }
    } catch (error) {
        notification.value = {
            show: true,
            title: 'Erreur',
            message: 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.',
        };
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");

@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }

    33% {
        transform: translate(30px, -50px) scale(1.1);
    }

    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }

    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
