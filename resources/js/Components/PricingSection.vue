<template>
    <section id="tarifs" class="py-20 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <h2
                    class="text-base text-indigo-600 font-semibold tracking-wide uppercase animate-fade-in"
                >
                    Tarifs
                </h2>
                <p
                    class="mt-2 text-4xl font-extrabold text-gray-900 animate-slide-up"
                >
                    Choisissez le plan adapté à
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600"
                        >vos besoins</span
                    >
                </p>
            </div>

            <div
                class="flex flex-col lg:flex-row justify-center items-center lg:items-stretch gap-8"
            >
                <div
                    v-for="(plan, index) in pricingPlans"
                    :key="plan.title"
                    class="w-full lg:w-1/3 pricing-card relative p-8 bg-white border border-gray-200 rounded-2xl shadow-lg flex flex-col transition-all duration-300 hover:shadow-2xl"
                    :class="{
                        'lg:order-2 z-1 transform lg:scale-110 border-indigo-500':
                            plan.title === 'Professionnel',
                        'lg:order-1': index === 0,
                        'lg:order-3': index === 2,
                        'hover:-translate-y-2': plan.title !== 'Professionnel',
                    }"
                >
                    <div
                        class="absolute -top-4 left-1/2 transform -translate-x-1/2"
                    ></div>
                    <h3 class="text-2xl font-bold text-gray-900 mt-6 mb-4">
                        {{ plan.title }}
                    </h3>
                    <p class="mb-6 text-gray-600">{{ plan.description }}</p>
                    <div class="flex items-baseline mb-8">
                        <span
                            class="text-5xl font-extrabold"
                            :class="
                                plan.title === 'Professionnel'
                                    ? 'text-indigo-600'
                                    : 'text-gray-900'
                            "
                            >{{ plan.price }}</span
                        >
                        <span class="ml-1 text-xl font-semibold text-gray-500"
                            >{{ plan.currency }}/mois</span
                        >
                    </div>
                    <ul class="mb-8 space-y-4">
                        <li
                            v-for="feature in plan.features"
                            :key="feature"
                            class="flex items-center"
                        >
                            <i
                                class="bi bi-check-circle-fill text-indigo-500 mr-2"
                            ></i>
                            <span class="text-gray-600">{{ feature }}</span>
                        </li>
                    </ul>
                    <a
                        @click="selectPlan(plan)"
                        href="#"
                        class="mt-auto py-3 px-6 text-white rounded-lg font-semibold text-center transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg"
                        :class="
                            plan.title === 'Professionnel'
                                ? 'bg-gradient-to-r from-indigo-500 to-purple-600'
                                : 'bg-gray-800 hover:bg-gray-700'
                        "
                    >
                        {{ plan.buttonText }}
                    </a>
                    <span
                        v-if="plan.badge"
                        class="absolute top-0 right-0 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-sm font-bold px-3 py-1 rounded-bl-lg rounded-tr-xl"
                    >
                        {{ plan.badge }}
                    </span>
                </div>
            </div>

            <div
                class="absolute top-1/2 left-0 w-72 h-72 bg-purple-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"
            ></div>
            <div
                class="absolute top-1/2 right-0 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"
            ></div>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";

const pricingPlans = ref([
    {
        title: "Débutant",
        description: "Parfait pour commencer votre gestion locative",
        currency: "XOF",
        price: "0",
        icon: "bi-house-door",
        features: [
            "Jusqu'à 10 propriétés",
            "Gestion des bailleurs et locataires",
            "Facturation de base",
            "Rapports mensuels",
        ],
        buttonText: "Commencer gratuitement",
    },
    {
        title: "Professionnel",
        description: "La solution idéale pour développer votre activité",
        currency: "XOF",
        price: "120 000",
        icon: "bi-building",
        features: [
            "Jusqu'à 100 propriétés",
            "Toutes les fonctionnalités Débutant",
            "Génération de documents avancée",
            "Gestion financière complète",
            "Support prioritaire",
        ],
        buttonText: "Choisir ce plan",
        badge: "RECOMMANDÉ",
    },
    {
        title: "Entreprise",
        description: "Solutions sur mesure pour les grands portefeuilles",
        currency: "XOF",
        price: "200 000",
        icon: "bi-graph-up-arrow",
        features: [
            "Propriétés illimitées",
            "Toutes les fonctionnalités Professionnel",
            "API pour intégrations personnalisées",
            "Tableau de bord personnalisé",
            "Formation et support dédié",
        ],
        buttonText: "Contacter les ventes",
    },
]);

const selectPlan = (plan) => {
    console.log(`Plan sélectionné : ${plan.title}`);
    // Ajoutez ici la logique pour la sélection du plan
};
</script>

<style scoped>
/* Les styles restent les mêmes que dans la version précédente */
</style>
