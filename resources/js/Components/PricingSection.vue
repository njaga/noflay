<template>
    <section id="tarifs" class="py-20 bg-white overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase animate-fade-in">
                    Tarifs
                </h2>
                <p class="mt-2 text-4xl font-extrabold text-gray-900 animate-slide-up">
                    Choisissez le plan adapté à
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">vos
                        besoins</span>
                </p>
            </div>

            <div class="flex flex-col lg:flex-row justify-center items-center lg:items-stretch gap-8">
                <div v-for="(plan, index) in pricingPlans" :key="plan.title"
                    class="w-full lg:w-1/3 pricing-card relative p-8 bg-white border border-gray-200 rounded-2xl shadow-lg flex flex-col transition-all duration-300 hover:shadow-2xl"
                    :class="{
                        'lg:order-2 z-1 transform lg:scale-110 border-indigo-500': plan.title === 'Professionnel',
                        'lg:order-1': index === 0,
                        'lg:order-3': index === 2,
                        'hover:-translate-y-2': plan.title !== 'Professionnel',
                    }">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2"></div>
                    <h3 class="text-2xl font-bold text-gray-900 mt-6 mb-4">
                        {{ plan.title }}
                    </h3>
                    <p class="mb-6 text-gray-600">{{ plan.description }}</p>
                    <div class="flex items-baseline mb-8">
                        <span class="text-4xl font-extrabold"
                            :class="plan.title === 'Professionnel' ? 'text-indigo-600' : 'text-gray-900'">
                            {{ plan.price }}
                        </span>
                        <span class="ml-1 text-xl font-semibold text-gray-500">
                            {{ plan.currency }}/mois
                        </span>
                    </div>
                    <ul class="mb-8 space-y-4">
                        <li v-for="feature in plan.features" :key="feature" class="flex items-center">
                            <i class="bi bi-check-circle-fill text-indigo-500 mr-2"></i>
                            <span class="text-gray-600">{{ feature }}</span>
                        </li>
                    </ul>
                    <a @click.prevent="selectPlan(plan)" href="#"
                        class="mt-auto py-3 px-6 text-white rounded-lg font-semibold text-center transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg"
                        :class="plan.title === 'Professionnel' ? 'bg-gradient-to-r from-indigo-500 to-purple-600' : 'bg-gray-800 hover:bg-gray-700'">
                        {{ plan.buttonText }}
                    </a>
                    <span v-if="plan.badge"
                        class="absolute top-0 right-0 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-sm font-bold px-3 py-1 rounded-bl-lg rounded-tr-xl">
                        {{ plan.badge }}
                    </span>
                </div>
            </div>

            <div
                class="absolute top-1/2 left-0 w-72 h-72 bg-purple-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
            </div>
            <div
                class="absolute top-1/2 right-0 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
            </div>
        </div>

        <!-- Modal de redirection -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Redirection simulée</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            En environnement de production, vous seriez redirigé vers :
                        </p>
                        <a :href="redirectUrl" target="_blank" rel="noopener noreferrer"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 mt-2 block break-all underline">
                            {{ redirectUrl }}
                        </a>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button @click="closeModal"
                            class="px-4 py-2 bg-indigo-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";
import axios from 'axios';

const pricingPlans = ref([
    {
        title: "Débutant",
        description: "Idéal pour les petits propriétaires",
        currency: "XOF",
        price: "50000",
        icon: "bi-house-door",
        features: [
            "Jusqu'à 10 propriétés",
            "Gestion des bailleurs et locataires",
            "Facturation de base",
            "Rapports mensuels",
        ],
        buttonText: "Commencer maintenant",
        buttonClass: "bg-blue-500 hover:bg-blue-600",
    },
    {
        title: "Professionnel",
        description: "La solution idéale pour développer votre activité",
        currency: "XOF",
        price: "120000",
        icon: "bi-building",
        features: [
            "Jusqu'à 100 propriétés",
            "Toutes les fonctionnalités Débutant",
            "Génération de documents avancée",
            "Gestion financière complète",
            "Support prioritaire",
        ],
        buttonText: "Choisir ce plan",
        buttonClass: "bg-gradient-to-r from-indigo-500 to-purple-600",
        badge: "RECOMMANDÉ",
    },
    {
        title: "Entreprise",
        description: "Solutions sur mesure pour les grands portefeuilles",
        currency: "XOF",
        price: "200000",
        icon: "bi-graph-up-arrow",
        features: [
            "Propriétés illimitées",
            "Toutes les fonctionnalités Professionnel",
            "API pour intégrations personnalisées",
            "Tableau de bord personnalisé",
            "Formation et support dédié",
        ],
        buttonText: "Souscrire",
        buttonClass: "bg-green-500 hover:bg-green-600",
    },
]);

const API_KEY = import.meta.env.VITE_PAYTECH_API_KEY;
const API_SECRET = import.meta.env.VITE_PAYTECH_API_SECRET;

const API_BASE_URL = "https://paytech.sn/api";

const showModal = ref(false);
const redirectUrl = ref('');

const selectPlan = async (plan) => {
    console.log(`Plan sélectionné : ${plan.title}`);

    const paymentData = {
        item_name: plan.title,
        item_price: parseInt(plan.price),
        currency: 'XOF',
        command_name: `Abonnement ${plan.title}`,
        env: 'test',
        ipn_url: 'https://ndiagandiaye.com/api/my-ipn',
        success_url: 'https://ndiagandiaye.com/paiement-reussi',
        cancel_url: 'https://ndiagandiaye.com/paiement-annule',
        ref_command: `REF-${Date.now()}`,
        custom_field: JSON.stringify({ planId: plan.id })
    };

    console.log('Données de paiement envoyées:', paymentData);

    try {
        const response = await axios.post(`${API_BASE_URL}/payment/request-payment`, paymentData, {
            headers: {
                'API_KEY': API_KEY,
                'API_SECRET': API_SECRET,
                'Content-Type': 'application/json'
            }
        });

        console.log('Réponse de Paytech:', response.data);

        if (response.data && response.data.success) {
            if (process.env.NODE_ENV === 'development') {
                redirectUrl.value = response.data.redirect_url;
                showModal.value = true;
            } else {
                window.location.href = response.data.redirect_url;
            }
        } else {
            console.error('Erreur lors de l\'initialisation du paiement:', response.data);
            alert(`Erreur: ${response.data.message || 'Une erreur est survenue lors de l\'initialisation du paiement.'}`);
        }
    } catch (error) {
        console.error('Erreur lors de la requête à Paytech:', error);
        if (error.response) {
            console.error('Réponse d\'erreur:', error.response.data);
            if (error.response.data.error && Array.isArray(error.response.data.error)) {
                const errorMessages = error.response.data.error.map(err => err.message).join(', ');
                alert(`Erreurs: ${errorMessages}`);
            } else {
                alert(`Erreur: ${error.response.data.message || 'Une erreur est survenue lors de la communication avec le service de paiement.'}`);
            }
        } else {
            alert('Une erreur est survenue lors de la communication avec le service de paiement. Veuillez réessayer.');
        }
    }
};

const closeModal = () => {
    showModal.value = false;
};
</script>

<style scoped>
@keyframes float {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-10px);
    }

    100% {
        transform: translateY(0px);
    }
}

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

.pricing-card {
    animation: float 6s ease-in-out infinite;
}

.text-transparent.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

@keyframes fade-in {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

@keyframes slide-up {
    0% {
        transform: translateY(20px);
        opacity: 0;
    }

    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.animate-slide-up {
    animation: slide-up 1s ease-out;
}
</style>
