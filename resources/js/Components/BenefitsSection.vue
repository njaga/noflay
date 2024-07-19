<template>
    <section id="avantages" class="py-20 bg-gradient-to-br from-blue-50 to-indigo-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase animate-fade-in">Avantages
                </h2>
                <p class="mt-2 text-4xl font-extrabold text-gray-900 animate-slide-up">
                    Pourquoi choisir <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Noflay</span>
                    ?
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div v-for="(benefit, index) in benefits" :key="benefit.title" class="relative">
                    <div class="benefit-card bg-white rounded-lg shadow-lg p-8 transition-all duration-300 hover:shadow-2xl"
                        :class="{ 'animate-slide-left': index === 0, 'animate-slide-up': index === 1, 'animate-slide-right': index === 2 }">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                            <div class="hexagon bg-gradient-to-br from-indigo-500 to-purple-600">
                                <i :class="['bi', benefit.icon, 'text-3xl text-white']"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mt-12 mb-4">{{ benefit.title }}</h3>
                        <p class="text-gray-600">{{ benefit.description }}</p>
                    </div>
                </div>
            </div>
            <div
                class="absolute top-1/2 left-0 w-full h-1/2 bg-gradient-to-t from-indigo-100 to-transparent transform -skew-y-6 -z-10">
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';

const benefits = ref([
    {
        icon: 'bi-rocket-takeoff',
        title: 'Boostez votre productivité',
        description: 'Automatisez vos tâches et concentrez-vous sur ce qui compte vraiment pour faire décoller votre activité.',
    },
    {
        icon: 'bi-shield-lock',
        title: 'Sécurité renforcée',
        description: 'Protégez vos intérêts et ceux de vos locataires grâce à notre système de gestion ultra-sécurisé.',
    },
    {
        icon: 'bi-graph-up-arrow',
        title: 'Maximisez vos profits',
        description: 'Optimisez vos revenus locatifs grâce à des outils d\'analyse avancés et une gestion financière intelligente.',
    },
]);
</script>

<style scoped>
.hexagon {
    width: 80px;
    height: 90px;
    background: linear-gradient(to bottom right, #6366f1, #8b5cf6);
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    display: flex;
    justify-content: center;
    align-items: center;
    transition: transform 0.3s ease;
}

.benefit-card:hover .hexagon {
    transform: rotate(360deg);
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideLeft {
    from {
        transform: translateX(-50px);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideRight {
    from {
        transform: translateX(50px);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.animate-slide-left {
    animation: slideLeft 0.8s ease-out;
}

.animate-slide-right {
    animation: slideRight 0.8s ease-out;
}
</style>
