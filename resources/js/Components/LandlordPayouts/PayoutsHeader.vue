<template>
    <div class="p-6 sm:p-10 bg-gradient-to-r from-indigo-600 to-indigo-700">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <div class="text-white mb-4 sm:mb-0">
                <h2 class="text-3xl font-bold flex items-center">
                    <i class="bi bi-graph-up-arrow text-white mr-3 text-4xl"></i>
                    Versements des Bailleurs
                </h2>
                <p class="mt-2 text-blue-100 font-light">Suivi financier et gestion des paiements</p>
            </div>
            <button
                @click="$emit('new-payout')"
                class="px-6 py-3 bg-white text-indigo-900 rounded-full shadow-lg hover:bg-gray-100 transition-all duration-200 flex items-center font-semibold text-sm"
            >
                <i class="bi bi-plus-circle-fill mr-2"></i>
                Nouveau versement
            </button>
        </div>
    </div>
</template>

<script setup>
const emit =  defineEmits(['new-payout']);
</script>
