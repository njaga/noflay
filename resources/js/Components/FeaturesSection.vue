<template>
    <section id="fonctionnalites" class="py-20 bg-white overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase animate-pulse">
                    Avantages
                </h2>
                <p class="mt-2 text-4xl font-extrabold text-gray-900">
                    Découvrez nos
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">fonctionnalités uniques</span>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(feature, index) in displayedFeatures" :key="feature.title"
                    class="feature-card bg-white rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-2"
                    :style="{ animationDelay: `${index * 150}ms` }">
                    <div class="flex flex-col h-full">
                        <div class="feature-icon text-4xl mb-6 text-indigo-500 transition-all duration-300 group-hover:scale-110 group-hover:text-purple-600">
                            <i :class="['bi', feature.icon]"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            {{ feature.title }}
                        </h3>
                        <p class="text-gray-600 mb-6 flex-grow">
                            {{ feature.description }}
                        </p>
                        <ul class="space-y-2 mt-auto">
                            <li v-for="subFeature in feature.subFeatures" :key="subFeature"
                                class="flex items-center text-sm text-gray-500">
                                <i class="bi bi-check-circle-fill mr-2 text-indigo-500"></i>
                                {{ subFeature }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-20 text-center">
                <button @click="toggleAllFeatures"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-300 animate-bounce">
                    {{ showAllFeatures ? 'Masquer les fonctionnalités supplémentaires' : 'Découvrir toutes nos fonctionnalités' }}
                    <i :class="['bi', showAllFeatures ? 'bi-arrow-up' : 'bi-arrow-down', 'ml-2']"></i>
                </button>
            </div>

            <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
            <div class="absolute top-0 right-0 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from "vue";

const showAllFeatures = ref(false);

const allFeatures = [
    {
        icon: "bi-person-check-fill",
        title: "Gestion des locataires",
        description: "Simplifiez la gestion de vos locataires avec notre plateforme.",
        subFeatures: [
            "Création et suivi des dossiers de location",
            "Gestion des paiements et des quittances",
            "Notifications et rappels automatiques",
        ],
    },
    {
        icon: "bi-file-earmark-text-fill",
        title: "Gestion des documents",
        description: "Organisez tous vos documents en un seul endroit.",
        subFeatures: [
            "Archivage sécurisé",
            "Accès facile et rapide",
            "Partage de documents",
        ],
    },
    {
        icon: "bi-building-fill",
        title: "Gestion des contrats",
        description: "Créez, modifiez et suivez vos contrats facilement.",
        subFeatures: [
            "Modèles personnalisables",
            "Signatures électroniques",
            "Suivi des renouvellements",
        ],
    },
    {
        icon: "bi-people-fill",
        title: "Gestion des bailleurs",
        description: "Gérez efficacement les relations avec vos bailleurs.",
        subFeatures: [
            "Profils des bailleurs",
            "Suivi des communications",
            "Reporting personnalisé",
        ],
    },
    {
        icon: "bi-archive-fill",
        title: "Archivage des documents",
        description: "Conservez et organisez tous vos documents importants.",
        subFeatures: [
            "Stockage sécurisé",
            "Recherche avancée",
            "Gestion des versions",
        ],
    },
    {
        icon: "bi-gear-fill",
        title: "Facile à paramétrer",
        description: "Configurez rapidement votre espace selon vos besoins.",
        subFeatures: [
            "Interface intuitive",
            "Personnalisation avancée",
            "Assistance au paramétrage",
        ],
    },
    {
        icon: "bi-receipt",
        title: "Facturation",
        description: "Gérez votre facturation de manière simple et efficace.",
        subFeatures: [
            "Génération automatique de factures",
            "Suivi des paiements",
            "Relances automatisées",
        ],
    },
    {
        icon: "bi-cash-stack",
        title: "Gestion des finances",
        description: "Optimisez la gestion financière de vos biens immobiliers.",
        subFeatures: [
            "Tableau de bord financier",
            "Analyse des revenus et dépenses",
            "Prévisions budgétaires",
        ],
    },
];

const displayedFeatures = computed(() => {
    return showAllFeatures.value ? allFeatures : allFeatures.slice(0, 3);
});

const toggleAllFeatures = () => {
    showAllFeatures.value = !showAllFeatures.value;
};
</script>

<style scoped>
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
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
