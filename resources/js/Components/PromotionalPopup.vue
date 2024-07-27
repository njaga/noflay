<!-- PromotionalPopup.vue -->
<template>
    <Transition name="slide-fade">
        <div
            v-if="isVisible"
            class="fixed bottom-0 left-0 right-0 bg-gradient-to-r from-pink-700 to-pink-500 text-white p-4 shadow-lg min-h-[12rem] sm:min-h-[10rem] z-20"
        >
            <div
                class="container mx-auto flex flex-col sm:flex-row items-center justify-between h-full relative"
            >
                <div
                    class="flex-1 flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-4 sm:mb-0"
                >
                    <div class="animate-bounce">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-14 w-14 text-yellow-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"
                            />
                        </svg>
                    </div>
                    <div class="text-center sm:text-left">
                        <h2
                            class="font-bold text-3xl sm:text-2xl animate-pulse"
                        >
                            Promo
                        </h2>
                        <p class="text-base sm:text-lg">Lancement de l'application Noflay</p>
                        <p class="text-base sm:text-lg mb-1">
                            Prix promotionnel :
                            <span class="font-bold text-yellow-300"
                                >30% de remise</span
                            >
                            jusqu'au 1ᵉʳ janvier 2025
                        </p>
                    </div>
                </div>
                <div
                    class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4"
                >
                    <button
                        @click="contact"
                        class="bg-white text-pink-600 px-6 py-2 rounded-full font-bold hover:bg-yellow-300 transition duration-300 transform hover:scale-105 w-full sm:w-auto"
                    >
                        Contactez-nous
                    </button>
                    <button
                        @click="close"
                        class="text-white hover:text-yellow-300 focus:outline-none transition duration-300 absolute top-2 right-2 sm:static"
                    >
                        <svg
                            class="h-6 w-6 sm:h-8 sm:w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const isVisible = ref(false);
let timer;

const close = () => {
    isVisible.value = false;
};

const contact = () => {
    console.log("Contacter Noflay");
    window.location.href = "mailto:contact@ndiagandiaye.com";
};

onMounted(() => {
    setTimeout(() => {
        isVisible.value = true;
    }, 1000);

    timer = setTimeout(() => {
        isVisible.value = false;
    }, 10000);
});

onUnmounted(() => {
    clearTimeout(timer);
});
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateY(100%) scale(0.9);
    opacity: 0;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes bounce {
    0%,
    100% {
        transform: translateY(-25%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}
</style>
