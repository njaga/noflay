<template>
    <AppLayout title="Compte Bailleur">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-3xl font-bold mb-8 text-gray-800">Compte Bailleur</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <StatCard title="Revenus des Loyers" :value="formatCurrency(totalRentIncome)" icon="home" />
                            <StatCard title="Dépenses" :value="formatCurrency(totalExpenses)" icon="shopping-cart" />
                            <StatCard title="Commissions" :value="formatCurrency(totalCommissions)" icon="dollar-sign" />
                            <StatCard title="Solde Net" :value="formatCurrency(netBalance)" icon="balance-scale" />
                        </div>
                        <div class="mt-8">
                            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Détails des Transactions</h3>
                            <DataSection title="Transactions" :data="transactions" :columns="columns" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import DataSection from '@/Components/DataSection.vue';

const { props } = usePage();
const landlord = ref(props.landlord || {});
const transactions = ref(props.transactions || []);

const totalRentIncome = computed(() => transactions.value.filter(t => t.type === 'rent').reduce((acc, t) => acc + t.amount, 0));
const totalExpenses = computed(() => transactions.value.filter(t => t.type === 'expense').reduce((acc, t) => acc + t.amount, 0));
const totalCommissions = computed(() => transactions.value.filter(t => t.type === 'commission').reduce((acc, t) => acc + t.amount, 0));
const netBalance = computed(() => totalRentIncome.value - totalExpenses.value - totalCommissions.value);

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'date', label: 'Date', format: value => formatDate(value) },
    { key: 'description', label: 'Description' },
    { key: 'amount', label: 'Montant', format: value => formatCurrency(value) },
    { key: 'type', label: 'Type' },
];

function formatCurrency(value) {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(value);
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200;
}

.btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold text-sm shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200;
}
</style>
