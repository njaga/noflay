<template>
    <section id="contact" class="bg-white text-gray-800 py-20 relative ">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase animate-fade-in">Contact
                </h2>
                <p class="mt-2 text-4xl font-extrabold text-gray-900 animate-slide-up">
                    Pour plus d'informations <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">ou support</span>
                </p>
            </div>

            <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div
                        class="p-10 bg-gradient-to-br from-indigo-600 to-purple-700 text-white flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-semibold mb-4">Informations de contact</h3>
                            <p class="mb-14">Remplissez le formulaire et notre équipe <br> vous répondra dans les 24 heures.
                            </p>
                            <div class="flex items-center mb-3">
                                <i class="bi bi-geo-alt-fill mr-3 text-xl"></i>
                                <span>Scat Urbam, Dakar, Sénégal</span>
                            </div>
                            <div class="flex items-center mb-3">
                                <i class="bi bi-telephone-fill mr-3 text-xl"></i>
                                <span>+221 78 163 34 19</span>
                            </div>
                            <div class="flex items-center mb-3">
                                <i class="bi bi-envelope-fill mr-3 text-xl"></i>
                                <span>contact@noflay.com</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <h4 class="text-xl font-semibold mb-3">Suivez-nous</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="text-white hover:text-pink-300 transition-colors duration-300">
                                    <i class="bi bi-facebook text-2xl"></i>
                                </a>
                                <a href="#" class="text-white hover:text-pink-300 transition-colors duration-300">
                                    <i class="bi bi-twitter text-2xl"></i>
                                </a>
                                <a href="#" class="text-white hover:text-pink-300 transition-colors duration-300">
                                    <i class="bi bi-instagram text-2xl"></i>
                                </a>
                                <a href="#" class="text-white hover:text-pink-300 transition-colors duration-300">
                                    <i class="bi bi-linkedin text-2xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-10">
                        <form @submit.prevent="submitContactForm">
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom
                                    complet</label>
                                <input type="text" id="name" v-model="form.name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" />
                            </div>
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" v-model="form.email" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" />
                            </div>
                            <div class="mb-6">
                                <label for="message"
                                    class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                <textarea id="message" v-model="form.message" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-300"></textarea>
                            </div>
                            <div>
                                <button type="submit" :disabled="isLoading"
                                    class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="mr-2">{{ isLoading ? 'Envoi en cours' : 'Envoyer le message' }}</span>
                                    <i v-if="!isLoading" class="bi bi-send-fill"></i>
                                    <i v-else class="bi bi-hourglass-split animate-spin"></i>
                                </button>
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
import { ref, onMounted } from 'vue';
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

const submitContactForm = async () => {
    isLoading.value = true;
    try {
        const response = await axios.post('/contact', form.value);
        if (response.data.success) {
            notification.value = {
                show: true,
                title: 'Message envoyé',
                message: 'Votre message a été envoyé avec succès.',
            };
            form.value = { name: '', email: '', message: '' };
        } else {
            notification.value = {
                show: true,
                title: 'Erreur',
                message: 'Une erreur est survenue lors de l\'envoi de votre message.',
            };
        }
    } catch (error) {
        notification.value = {
            show: true,
            title: 'Erreur',
            message: 'Une erreur est survenue lors de l\'envoi de votre message.',
        };
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-fade-in-up').forEach(el => observer.observe(el));
});
</script>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");

@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
