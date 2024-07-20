<template>
    <div v-if="financialInfo.length" class="mt-8 bg-gray-50 p-6 rounded-lg">
        <h3 class="text-2xl font-bold text-gray-700 mb-4">
            Informations financières
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                v-for="(info, index) in financialInfo"
                :key="index"
                class="bg-white p-4 rounded-md shadow transition-all duration-300 hover:shadow-md"
            >
                <div class="flex items-center justify-between mb-2">
                    <i :class="['bi', info.icon, info.color]"></i>
                    <p class="text-sm font-medium text-gray-500">
                        {{ info.label }}
                    </p>
                </div>
                <p class="text-lg font-bold" :class="info.color">
                    {{ formatCurrency(info.amount) }}
                </p>
                <ul class="mt-2 text-sm text-gray-600">
                    <li
                        v-for="detail in info.details"
                        :key="detail.property + '-' + detail.amount"
                    >
                        {{ detail.tenant }} - {{ detail.property }} :
                        {{ formatCurrency(detail.amount) }}
                    </li>
                </ul>
            </div>
        </div>
        <div
            v-if="financialInfo.length === 0"
            class="text-center text-gray-500 mt-4"
        >
            Aucun solde disponible
        </div>
    </div>
</template>

<script>
export default {
    props: {
        financialInfo: {
            type: Array,
            required: true,
        },
    },
    methods: {
        formatCurrency(amount) {
            return new Intl.NumberFormat("fr-FR", {
                style: "currency",
                currency: "XOF",
            }).format(amount);
        },
    },
};
</script>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");

.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}
</style>
