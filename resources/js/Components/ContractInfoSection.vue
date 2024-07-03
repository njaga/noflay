<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div v-for="(value, key) in info" :key="key" class="flex items-start">
            <i :class="['fas', getIcon(key), 'mr-3', 'text-gray-500', 'mt-1']"></i>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">{{ formatLabel(key) }}</p>
                <p class="text-base text-gray-900">{{ formatValue(key, value) }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    info: {
        type: Object,
        required: true
    }
});

const getIcon = (key) => {
    const icons = {
        nom: 'fa-user',
        adresse: 'fa-map-marker-alt',
        telephone: 'fa-phone',
        email: 'fa-envelope',
        'numéro d\'identification nationale': 'fa-id-card',
        profession: 'fa-briefcase',
        'type de contrat': 'fa-file-contract',
        'adresse du bien loué': 'fa-home',
        'date de début': 'fa-calendar-alt',
        'date de fin': 'fa-calendar-check',
        'durée du bail': 'fa-clock',
        'montant du loyer': 'fa-money-bill-wave',
        'fréquence de paiement': 'fa-calendar',
        'méthode de paiement': 'fa-credit-card',
        'dépôt de garantie': 'fa-piggy-bank',
        'charges incluses': 'fa-check-circle',
        'charges non incluses': 'fa-times-circle'
    };
    return icons[key.toLowerCase()] || 'fa-info-circle';
};

const formatLabel = (key) => {
    return key.charAt(0).toUpperCase() + key.slice(1);
};

const formatValue = (key, value) => {
    if (typeof value === 'number') {
        return new Intl.NumberFormat('fr-SN', { style: 'currency', currency: 'XOF' }).format(value);
    }
    if (value instanceof Date) {
        return value.toLocaleDateString('fr-SN', { day: '2-digit', month: 'long', year: 'numeric' });
    }
    if (Array.isArray(value)) {
        return value.join(', ');
    }
    return value || "Non spécifié";
};
</script>

<style scoped>
.grid {
    @apply bg-white rounded-lg shadow-sm p-4;
}

.text-gray-500 {
    @apply text-opacity-75;
}

.text-gray-900 {
    @apply font-medium;
}

i {
    @apply text-lg;
}
</style>
