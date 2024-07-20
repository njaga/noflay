<!-- resources/js/Components/SummaryCards.vue -->
<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div v-for="(stat, index) in stats" :key="index" class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ stat.title }}</h3>
            <p class="text-3xl font-bold text-indigo-600">
                {{
                    index !== 2 ? formatCurrency(stat.value) : stat.value
                }}
            </p>
            <p class="text-sm text-gray-500 mt-2">{{ stat.description }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    stats: {
        type: Array,
        required: true,
    }
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(amount);
};
</script>
