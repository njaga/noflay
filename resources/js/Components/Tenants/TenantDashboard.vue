<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div v-for="(card, index) in cards" :key="index"
            class="bg-white rounded-2xl shadow-lg p-6 flex items-center space-x-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 cursor-pointer">
            <div :class="card.bgColor" class="p-3 rounded-full">
                <i :class="card.icon" class="text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">{{ card.title }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ card.value }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    activeTenantCount: Number,
    inactiveTenantCount: Number,
    expiringContracts: Number,
});

const cards = computed(() => [
    {
        icon: 'fas fa-user-check text-green-600',
        title: 'Locataires actifs',
        value: props.activeTenantCount,
        bgColor: 'bg-green-100',
    },
    {
        icon: 'fas fa-user-times text-red-600',
        title: 'Locataires inactifs',
        value: props.inactiveTenantCount,
        bgColor: 'bg-red-100',
    },
    {
        icon: 'fas fa-exclamation-triangle text-yellow-600',
        title: 'Contrats expirant ce mois',
        value: props.expiringContracts,
        bgColor: 'bg-yellow-100',
    },
]);
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
</style>
