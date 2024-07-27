<template>
    <Loader />
    <div class="font-sans antialiased bg-gray-100">
        <Head title="Noflay - Simplifiez votre gestion locative" />
        <Navigation @open-login-modal="loginModalOpen = true" @open-demo-modal="demoModalOpen = true" />
        <HeroSection @open-demo-modal="demoModalOpen = true" />
        <FeaturesSection />
        <BenefitsSection />
        <PricingSection @select-plan="selectPlan" />
        <ContactSection @contact-submitted="handleContactForm" />
        <Footer />
        <PromotionalPopup />
        <LoginModal v-if="loginModalOpen" @close="loginModalOpen = false" @login="handleLogin" />
        <DemoModal v-if="demoModalOpen" @close="demoModalOpen = false" @request-sent="handleDemoRequestSent" />
        <NotificationContactModal
            v-if="notificationModalOpen"
            :title="notificationTitle"
            :message="notificationMessage"
            :type="notificationType"
            @close="notificationModalOpen = false"
        />
        <ChatWidget />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import Navigation from '@/Components/Navigation.vue';
import HeroSection from '@/Components/HeroSection.vue';
import FeaturesSection from '@/Components/FeaturesSection.vue';
import BenefitsSection from '@/Components/BenefitsSection.vue';
import PricingSection from '@/Components/PricingSection.vue';
import ContactSection from '@/Components/ContactSection.vue';
import Footer from '@/Components/Footer.vue';
import LoginModal from '@/Components/LoginModal.vue';
import DemoModal from '@/Components/DemoModal.vue';
import NotificationContactModal from '@/Components/NotificationContactModal.vue';
import ChatWidget from '@/Components/ChatWidget.vue';
import axios from 'axios';
import Loader from '@/Components/Loader.vue';
import PromotionalPopup from '@/Components/PromotionalPopup.vue';

const loginModalOpen = ref(false);
const demoModalOpen = ref(false);
const notificationModalOpen = ref(false);
const notificationTitle = ref('');
const notificationMessage = ref('');
const notificationType = ref('success');

const messages = {
    login: {
        success: {
            title: 'Connexion réussie',
            message: 'Vous êtes maintenant connecté.',
        },
        error: {
            title: 'Erreur de connexion',
            message: 'Une erreur est survenue lors de la tentative de connexion.',
        },
    },
    contact: {
        success: {
            title: 'Message envoyé',
            message: 'Votre message a été envoyé avec succès.',
        },
        error: {
            title: 'Erreur',
            message: 'Une erreur est survenue lors de l\'envoi de votre message.',
        },
    },
    demo: {
        success: {
            title: 'Demande de démo envoyée',
            message: 'Votre demande de démo a été envoyée avec succès.',
        },
        error: {
            title: 'Erreur',
            message: 'Une erreur est survenue lors de l\'envoi de votre demande de démo.',
        },
    },
};

const handleLogin = async (credentials) => {
    try {
        const response = await axios.post('/login', credentials, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.status === 200) {
            notificationTitle.value = messages.login.success.title;
            notificationMessage.value = messages.login.success.message;
            notificationType.value = 'success';
            notificationModalOpen.value = true;
            loginModalOpen.value = false;
            window.location.href = '/dashboard';
        } else {
            throw new Error('Échec de la connexion');
        }
    } catch (error) {
        notificationTitle.value = messages.login.error.title;
        notificationMessage.value = error.response?.data?.message || messages.login.error.message;
        notificationType.value = 'error';
        notificationModalOpen.value = true;
    }
};

const handleContactForm = async (form) => {
    try {
        const response = await axios.post('/contact', form);
        if (response.data.success) {
            notificationTitle.value = messages.contact.success.title;
            notificationMessage.value = messages.contact.success.message;
            notificationType.value = 'success';
        } else {
            throw new Error('Échec de l\'envoi du message');
        }
    } catch (error) {
        notificationTitle.value = messages.contact.error.title;
        notificationMessage.value = messages.contact.error.message;
        notificationType.value = 'error';
    }
    notificationModalOpen.value = true;
};

const handleDemoRequestSent = (status) => {
    if (status === 'success') {
        notificationTitle.value = messages.demo.success.title;
        notificationMessage.value = messages.demo.success.message;
        notificationType.value = 'success';
    } else {
        notificationTitle.value = messages.demo.error.title;
        notificationMessage.value = messages.demo.error.message;
        notificationType.value = 'error';
    }
    notificationModalOpen.value = true;
};

const selectPlan = (plan) => {
    console.log(`Plan sélectionné : ${plan.title}`);
    // Implémenter la logique de sélection de plan ici
};

onMounted(() => {
    window.addEventListener('open-login-modal', () => {
        loginModalOpen.value = true;
    });

    window.addEventListener('open-demo-modal', () => {
        demoModalOpen.value = true;
    });
});
</script>

<style scoped>
/* Ajoutez vos styles personnalisés ici */
</style>
