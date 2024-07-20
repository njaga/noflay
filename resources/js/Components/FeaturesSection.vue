<template>
    <section id="fonctionnalites" class="py-20 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <h2
                    class="text-base text-indigo-600 font-semibold tracking-wide uppercase animate-pulse"
                >
                    Avantages
                </h2>
                <p class="mt-2 text-4xl font-extrabold text-gray-900">
                    Découvrez nos
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600"
                        >fonctionnalités uniques</span
                    >
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="(feature, index) in features"
                    :key="feature.title"
                    class="feature-card bg-white rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-2"
                    :style="{ animationDelay: `${index * 150}ms` }"
                >
                    <div class="flex flex-col h-full">
                        <div
                            class="feature-icon text-4xl mb-6 text-indigo-500 transition-all duration-300 group-hover:scale-110 group-hover:text-purple-600"
                        >
                            <i :class="['bi', feature.icon]"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            {{ feature.title }}
                        </h3>
                        <p class="text-gray-600 mb-6 flex-grow">
                            {{ feature.description }}
                        </p>
                        <ul class="space-y-2 mt-auto">
                            <li
                                v-for="subFeature in feature.subFeatures"
                                :key="subFeature"
                                class="flex items-center text-sm text-gray-500"
                            >
                                <i
                                    class="bi bi-check-circle-fill mr-2 text-indigo-500"
                                ></i>
                                {{ subFeature }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-20 text-center">
                <a
                    href="#"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-300 animate-bounce"
                >
                    Découvrir toutes nos fonctionnalités
                    <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>

            <div
                class="absolute top-0 left-0 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"
            ></div>
            <div
                class="absolute top-0 right-0 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"
            ></div>
            <div
                class="absolute bottom-0 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"
            ></div>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";

const features = ref([
    {
        icon: "bi-building-gear",
        title: "Gestion immobilière intelligente",
        description:
            "Optimisez votre parc immobilier avec notre technologie de pointe.",
        subFeatures: [
            "IA prédictive pour la maintenance",
            "Tableaux de bord personnalisables",
            "Alertes en temps réel",
        ],
    },
    {
        icon: "bi-people-fill",
        title: "Expérience locataire exceptionnelle",
        description: "Créez des relations durables avec vos locataires.",
        subFeatures: [
            "Application mobile intuitive",
            "Paiements en ligne sécurisés",
            "Assistance 24/7 via chatbot",
        ],
    },
    {
        icon: "bi-graph-up-arrow",
        title: "Analyse financière avancée",
        description: "Maximisez vos revenus avec des insights data-driven.",
        subFeatures: [
            "Prévisions de trésorerie IA",
            "Optimisation des loyers",
            "Rapports financiers automatisés",
        ],
    },
    {
        icon: "bi-shield-lock-fill",
        title: "Conformité et sécurité renforcées",
        description:
            "Protégez vos actifs et restez conforme aux réglementations.",
        subFeatures: [
            "Mises à jour légales automatiques",
            "Chiffrement de bout en bout",
            "Audits de sécurité réguliers",
        ],
    },
    {
        icon: "bi-bezier2",
        title: "Automatisation intelligente",
        description: "Libérez du temps avec nos workflows automatisés.",
        subFeatures: [
            "Processus personnalisables",
            "Intégrations API robustes",
            "Automatisation des tâches répétitives",
        ],
    },
    {
        icon: "bi-arrow-repeat",
        title: "Écosystème interconnecté",
        description: "Connectez tous vos outils pour une gestion unifiée.",
        subFeatures: [
            "Intégration avec les principaux logiciels",
            "Synchronisation en temps réel",
            "Plateforme ouverte et extensible",
        ],
    },
]);
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

.feature-card {
    animation: float 6s ease-in-out infinite;
}

.text-transparent.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}
</style>
