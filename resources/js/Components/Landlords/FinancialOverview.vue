<template>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                Vue d'ensemble financière
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <StatCard
                    title="Cautions"
                    :value="financialInfo.totalCautionAmount"
                    icon="credit-card"
                    color="bg-yellow-500"
                />
                <StatCard
                    title="Revenus des Loyers"
                    :value="financialInfo.monthlyRent"
                    icon="cash"
                    color="bg-green-500"
                />
                <StatCard
                    title="Dépenses"
                    :value="financialInfo.monthlyExpenses"
                    icon="cart"
                    color="bg-red-500"
                />
                <StatCard
                    title="Commissions"
                    :value="financialInfo.totalCommissions"
                    icon="credit-card"
                    color="bg-blue-500"
                />
                <StatCard
                    title="Solde Net"
                    :value="financialInfo.netBalance"
                    icon="wallet"
                    color="bg-indigo-500"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import StatCard from "@/Components/StatCard.vue";

const props = defineProps({
    financialInfo: {
        type: Object,
        required: true
    }
});
</script>
