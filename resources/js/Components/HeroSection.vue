<template>
    <header
        class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 text-white min-h-screen flex items-center">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-grid-indigo-300/[0.2] bg-[size:20px_20px]"></div>
            <div class="absolute inset-0 backdrop-blur-sm"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-16 sm:py-24">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="lg:w-1/2 mb-12 lg:mb-0">
                    <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold tracking-tight leading-none mb-6 mt-10">
                        <span
                            class="block text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-300 animate-gradient">Simplifiez</span>
                        <span class="block mt-2">votre gestion</span>
                    </h1>
                    <p class="mt-6 text-xl text-indigo-200 max-w-md animate-fade-in">
                        NOFLAY simplifie la gestion immobilière avec des outils pour les utilisateurs, les propriétés,
                        la facturation, les mandats, les rapports, et les notifications en temps réel.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <button @click="openDemoModal" class="neon-button">
                            Demander une démo
                        </button>
                        <a href="#fonctionnalites" class="innovative-button group">
                            <span class="innovative-button-bg"></span>
                            <span class="relative">Découvrir</span>
                        </a>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div
                        class="relative z-10 rounded-lg overflow-hidden shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-300 group">
                        <img src="/img/mockup-accueil.png" alt="Dashboard de gestion locative"
                            class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-indigo-900 via-indigo-900/40 to-transparent opacity-60 group-hover:opacity-40 transition-opacity duration-300">
                        </div>
                    </div>
                    <div
                        class="absolute -bottom-6 -left-6 w-28 h-28 bg-white rounded-full flex items-center justify-center animate-bounce-slow z-20">
                        <span class="text-indigo-900 font-bold text-xl">Nouveau</span>
                    </div>
                    <div
                        class="absolute -top-4 -right-4 w-24 h-24 bg-pink-400 rounded-full flex items-center justify-center animate-pulse z-20">
                        <span class="text-white font-bold text-lg">2024</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full overflow-hidden rotate-180">
            <svg class="w-full h-auto" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    fill="rgba(199, 210, 254, 0.08)"></path>
            </svg>
        </div>
    </header>
</template>

<script setup>
import { defineEmits } from 'vue';

const emit = defineEmits(['open-demo-modal']);

const openDemoModal = () => {
    emit('open-demo-modal');
};
</script>

<style scoped>
@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

.animate-gradient {
    animation: gradient 6s ease infinite;
    background-size: 200% 200%;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

@keyframes bounce-slow {

    0%,
    100% {
        transform: translateY(-5%);
    }

    50% {
        transform: translateY(5%);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 3s infinite;
}

.neon-button {
    @apply px-8 py-4 bg-indigo-500 text-white font-semibold rounded-full transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 shadow-lg;
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.7);
    box-shadow: 0 0 10px rgba(99, 102, 241, 0.5), 0 0 20px rgba(99, 102, 241, 0.3), 0 0 30px rgba(99, 102, 241, 0.1);
}

.innovative-button {
    @apply relative px-8 py-4 bg-transparent border-2 border-purple-400 text-purple-300 font-semibold rounded-full overflow-hidden transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none;
}

.innovative-button-bg {
    @apply absolute inset-0 bg-purple-500 transition-transform duration-300 ease-in-out transform scale-x-0 origin-left;
}

.innovative-button:hover .innovative-button-bg {
    @apply scale-x-100;
}

.innovative-button:hover {
    @apply text-white;
}

.bg-grid-indigo-300 {
    background-image: linear-gradient(to right, rgba(199, 210, 254, 0.1) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(199, 210, 254, 0.1) 1px, transparent 1px);
}

@media (max-width: 640px) {
    header {
        @apply py-12;
    }
}
</style>
