<template>
    <section id="fonctionnalites" class="py-24 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="sr-only">Fonctionnalités de Noflay</h2>

            <!-- Intro avec typographie moderne -->
            <div class="mb-24 text-center">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
                    Révolutionnez <span class="text-indigo-600">votre gestion</span> immobilière
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez comment Noflay transforme la gestion immobilière avec des outils puissants et intuitifs.
                </p>
            </div>

            <!-- Fonctionnalités principales avec design épuré -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-32">
                <div v-for="(feature, index) in mainFeatures" :key="index"
                    class="bg-white p-8 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                    <div class="text-indigo-600 mb-4">
                        <component :is="feature.icon" class="w-12 h-12" />
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900">{{ feature.title }}</h3>
                    <p class="text-gray-600">{{ feature.description }}</p>
                </div>
            </div>

            <!-- Fonctionnalités détaillées avec design moderne -->
            <div class="space-y-32">
                <div v-for="(feature, index) in detailedFeatures" :key="index"
                    class="flex flex-col lg:flex-row items-center" :class="{ 'lg:flex-row-reverse': index % 2 !== 0 }">
                    <div class="w-full lg:w-1/2 mb-8 lg:mb-0">
                        <div class="relative overflow-hidden rounded-xl shadow-2xl">
                            <img :src="feature.image" :alt="feature.title" class="w-full h-auto" />
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-20">
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 lg:px-12">
                        <h3 class="text-3xl font-bold mb-6 text-gray-900">{{ feature.title }}</h3>
                        <p class="text-xl text-gray-600 mb-8">{{ feature.description }}</p>
                        <ul class="space-y-4">
                            <li v-for="(point, pIndex) in feature.points" :key="pIndex"
                                class="flex items-start text-gray-700">
                                <CheckIcon class="w-6 h-6 text-indigo-500 mr-3 flex-shrink-0" />
                                <span>{{ point }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Section sur l'application mobile avec design moderne -->
            <section class="mt-40 relative overflow-visible">
                <div class="absolute inset-x-0 top-0 h-2/3 bg-indigo-50 rounded-2xl"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-24">
                    <div class="lg:flex lg:items-start lg:justify-between">
                        <div class="lg:w-1/2 z-10 relative text-center lg:text-left">
                            <h3 class="text-3xl font-bold mb-6 text-gray-900">Bientôt sur Mobile</h3>
                            <p class="text-xl text-gray-600 mb-10">
                                Toute la puissance de Noflay dans votre poche. Soyez parmi les premiers à découvrir
                                notre application révolutionnaire.
                            </p>
                            <form @submit.prevent="submitMobileNotification" class="max-w-md mx-auto lg:mx-0">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <input
                                        class="flex-grow appearance-none bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:border-indigo-500"
                                        type="email" placeholder="Votre adresse e-mail"
                                        aria-label="Email pour notification" v-model="mobileNotificationEmail" required>
                                    <button
                                        class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300"
                                        type="submit">
                                        Notifiez-moi
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="lg:w-1/2 mt-12 lg:mt-0">
                            <div
                                class="relative w-full h-[400px] sm:h-[500px] lg:h-[600px] xl:h-[700px] -mt-40 lg:-mt-80">
                                <img src="/img/noflay-mobile.png" alt="Noflay Mobile App Mockup"
                                    class="absolute left-1/2 top-0 transform -translate-x-1/2 lg:left-auto lg:right-[1rem] lg:translate-x-0 w-auto h-full object-contain lg:object-cover" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>



        </div>
    </section>
</template>

<script>
import { ref } from 'vue';
import {
    CreditCardIcon,
    BanknotesIcon,
    ShieldCheckIcon,
    CurrencyDollarIcon,
    DocumentTextIcon,
    UsersIcon,
    ChartBarIcon,
    CheckIcon
} from '@heroicons/vue/24/outline';

export default {
    components: {
        CreditCardIcon,
        BanknotesIcon,
        ShieldCheckIcon,
        CurrencyDollarIcon,
        DocumentTextIcon,
        UsersIcon,
        ChartBarIcon,
        CheckIcon
    },
    setup() {
        const mainFeatures = ref([
            {
                title: 'Automatisation des Documents',
                description: 'Générez instantanément tous vos documents essentiels, conformes à la législation en vigueur.',
                icon: DocumentTextIcon,
            },
            {
                title: 'Gestion Complète',
                description: 'Centralisez la gestion de vos locataires, propriétaires et équipes en un seul endroit.',
                icon: UsersIcon,
            },
            {
                title: 'Finances & Comptabilité',
                description: 'Suivez vos revenus, dépenses et commissions en temps réel avec une précision inégalée.',
                icon: ChartBarIcon,
            },
        ]);

        const detailedFeatures = ref([
            {
                title: 'Documents Intelligents',
                description: 'Révolutionnez votre gestion documentaire grâce à notre système d\'automatisation de pointe.',
                image: '/img/smart-documents.png',
                points: [
                    'Génération instantanée de mandats de gérance',
                    'Contrats de location personnalisés',
                    'États des lieux avec signature électronique',
                    'Reçus et quittances automatisés',
                ],
            },
            {
                title: 'Gestion Financière Avancée',
                description: 'Optimisez vos performances financières avec nos outils d\'analyse sophistiqués.',
                image: '/img/financial-management.png',
                points: [
                    'Calcul automatique des commissions',
                    'Suivi détaillé des dépenses par propriété',
                    'Gestion des cotisations locatives',
                    'Rapports financiers sur mesure',
                ],
            },
            {
                title: 'Gestion d\'Équipe Simplifiée',
                description: 'Boostez l\'efficacité de votre agence grâce à nos outils de gestion d\'équipe innovants.',
                image: '/img/team-management.png',
                points: [
                    'Gestion centralisée du personnel',
                    'Système de permissions avancé',
                    'Collaboration en temps réel',
                    'Suivi des performances',
                ],
            },
        ]);

        const paymentOptions = ref([
            {
                icon: CreditCardIcon,
                description: 'Paiements par carte bancaire, PayPal, et plus',
            },
            {
                icon: BanknotesIcon,
                description: 'Options de paiement mobile (Wave, Orange Money, Free Money)',
            },
            {
                icon: ShieldCheckIcon,
                description: 'Transactions sécurisées et traçables',
            },
            {
                icon: CurrencyDollarIcon,
                description: 'Encaissements rapides pour les propriétaires',
            },
        ]);

        const mobileNotificationEmail = ref('');

        const submitMobileNotification = () => {
            console.log('Inscription à la notification mobile:', mobileNotificationEmail.value);
            mobileNotificationEmail.value = '';
            alert('Merci pour votre inscription ! Nous vous tiendrons informé de la sortie de notre application mobile.');
        };

        return {
            mainFeatures,
            detailedFeatures,
            paymentOptions,
            mobileNotificationEmail,
            submitMobileNotification,
        };
    },
};
</script>

<style scoped></style>
