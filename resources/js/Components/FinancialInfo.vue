<template>
    <div class="mt-8 bg-white p-8 rounded-xl shadow-lg">
        <h3 class="text-3xl font-semibold text-gray-800 mb-6">
            Informations financières
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div
                v-for="(info, index) in financialInfo"
                :key="index"
                class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow transition-transform transform hover:scale-105"
            >
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center"
                        :class="info.bgColor"
                    >
                        <i :class="['text-xl', info.icon]"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-600">
                        {{ info.label }}
                    </p>
                </div>
                <p class="text-2xl font-bold" :class="info.textColor">
                    {{ formatCurrency(info.amount) }}
                </p>
                <ul class="mt-4 text-sm text-gray-700">
                    <li v-for="detail in info.details" :key="detail.type">
                        <strong>{{ detail.type }}:</strong>
                        {{ formatCurrency(detail.amount) }}
                    </li>
                </ul>
            </div>
        </div>
        <div v-if="financialInfo.length === 0" class="text-center text-gray-500 mt-6">
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
.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}
</style>
