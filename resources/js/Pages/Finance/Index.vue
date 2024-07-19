<template>
    <AppLayout title="Finance">
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold mb-8 text-gray-900">Gestion de la finance</h2>

                <!-- Boutons de raccourcis -->
                <div class="flex justify-end mb-8">
                    <Link :href="route('expenses.create')" class="btn-primary mr-2">Ajouter une Dépense</Link>
                    <Link :href="route('expenses.create')" class="btn-primary mr-2">Ajouter une Paiement</Link>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <StatCard title="Total des Cautions" :value="formatCurrency(totalCautions)" icon="lock"
                        color="bg-indigo-500" />
                    <StatCard title="Total des Commissions" :value="formatCurrency(totalCommissions)" icon="dollar-sign"
                        color="bg-green-500" />
                    <StatCard title="Total des Loyers" :value="formatCurrency(totalRent)" icon="home"
                        color="bg-purple-500" />
                    <StatCard title="Total des Dépenses" :value="formatCurrency(totalExpenses)" icon="shopping-cart"
                        color="bg-red-500" />
                </div>

                <!-- Comparaison-->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Comparaison des Paiements</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-indigo-100 p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Mois Précédent</h4>
                            <p class="text-3xl font-bold text-indigo-600">{{ formatCurrency(previousMonthPayments) }}
                            </p>
                        </div>
                        <div class="bg-indigo-100 p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Mois en Cours</h4>
                            <p class="text-3xl font-bold text-indigo-600">{{ formatCurrency(currentMonthPayments) }}</p>
                        </div>
                        <div :class="{ 'bg-red-100': paymentsDifference < 0, 'bg-indigo-100': paymentsDifference >= 0 }"
                            class="p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Différence</h4>
                            <p :class="{ 'text-red-600': paymentsDifference < 0, 'text-indigo-600': paymentsDifference >= 0 }"
                                class="text-3xl font-bold">
                                {{ formatCurrency(paymentsDifference) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Filtres avancés</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="filter-tenant"
                                class="block text-sm font-medium text-gray-700 mb-1">Locataire</label>
                            <select id="filter-tenant" v-model="filters.tenant"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Tous les locataires</option>
                                <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                                    {{ tenant.first_name }} {{ tenant.last_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-property"
                                class="block text-sm font-medium text-gray-700 mb-1">Propriété</label>
                            <select id="filter-property" v-model="filters.property"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Toutes les propriétés</option>
                                <option v-for="property in properties" :key="property.id" :value="property.id">
                                    {{ property.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-date-from" class="block text-sm font-medium text-gray-700 mb-1">Date de
                                Début</label>
                            <input type="date" id="filter-date-from" v-model="filters.date_from"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label for="filter-date-to" class="block text-sm font-medium text-gray-700 mb-1">Date de
                                Fin</label>
                            <input type="date" id="filter-date-to" v-model="filters.date_to"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="applyFilters" class="btn-primary">
                            <i class="fas fa-search mr-2"></i>Rechercher
                        </button>
                        <button @click="resetFilters" class="btn-secondary">
                            <i class="fas fa-undo mr-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Sections de données -->
                <div>
                    <NavigationTabs :tabs="tabs" v-model="activeTab">
                        <template #cautions>
                            <DataSection title="Cautions" :data="filteredContracts" :columns="cautionColumns" />
                        </template>
                        <template #payments>
                            <DataSection title="Paiements" :data="filteredPayments" :columns="paymentColumns" />
                        </template>
                        <template #expenses>
                            <DataSection title="Dépenses" :data="filteredExpenses" :columns="expenseColumns" />
                        </template>
                        <template #commissions>
                            <DataSection title="Commissions" :data="filteredContracts" :columns="commissionColumns" />
                        </template>
                        <template #cash>
                            <DataSection title="Grand Livre Caisse" :data="filteredCashes" :columns="cashColumns" />
                        </template>
                        <template #ventilation>
                            <DataSection title="Ventilation Caisse" :data="filteredVentilations"
                                :columns="ventilationColumns" />
                        </template>
                    </NavigationTabs>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import DataSection from '@/Components/DataSection.vue';
import NavigationTabs from '@/Components/NavigationTabs.vue';

const { props } = usePage();

const contracts = ref(props.contracts || []);
const payments = ref(props.payments || []);
const expenses = ref(props.expenses || []);
const tenants = ref(props.tenants || []);
const properties = ref(props.properties || []);
const cashes = ref(props.cashes || []);
const ventilations = ref(props.ventilations || []);

const totalCautions = ref(props.totalCautions || 0);
const totalPayments = ref(props.totalPayments || 0);
const totalExpenses = ref(props.totalExpenses || 0);
const totalCommissions = ref(props.totalCommissions || 0);
const totalRent = ref(props.totalRent || 0);

const currentMonthPayments = ref(props.currentMonthPayments || 0);
const previousMonthPayments = ref(props.previousMonthPayments || 0);
const paymentsDifference = ref(props.paymentsDifference || 0);

const filters = ref({
    tenant: "",
    property: "",
    date_from: "",
    date_to: "",
});

const activeTab = ref('cautions');
const tabs = [
    { name: 'Cautions', id: 'cautions' },
    { name: 'Paiements', id: 'payments' },
    { name: 'Dépenses', id: 'expenses' },
    { name: 'Commissions', id: 'commissions' },
    { name: 'Grand Livre Caisse', id: 'cash' },
    { name: 'Ventilation Caisse', id: 'ventilation' },
];

const cautionColumns = [
    { key: 'id', label: 'ID' },
    { key: 'tenant', label: 'Locataire', format: (value) => `${value.first_name} ${value.last_name}` },
    { key: 'property', label: 'Propriété', format: (value) => value.name },
    { key: 'caution_amount', label: 'Montant', format: (value) => formatCurrency(value) },
];

const paymentColumns = [
    { key: 'id', label: 'ID' },
    { key: 'contract.tenant', label: 'Locataire', format: (value) => `${value.first_name} ${value.last_name}` },
    { key: 'contract.property', label: 'Propriété', format: (value) => value.name },
    { key: 'amount', label: 'Montant', format: (value) => formatCurrency(value) },
    { key: 'payment_date', label: 'Date de Paiement', format: (value) => formatDate(value) },
];

const expenseColumns = [
    { key: 'id', label: 'ID' },
    { key: 'description', label: 'Description' },
    { key: 'property', label: 'Propriété', format: (value) => value.name },
    { key: 'amount', label: 'Montant', format: (value) => formatCurrency(value) },
    { key: 'expense_date', label: 'Date de Dépense', format: (value) => formatDate(value) },
];

const commissionColumns = [
    { key: 'id', label: 'ID' },
    { key: 'tenant', label: 'Locataire', format: (value) => `${value.first_name} ${value.last_name}` },
    { key: 'property', label: 'Propriété', format: (value) => value.name },
    { key: 'commission_amount', label: 'Montant', format: (value) => formatCurrency(value) },
];

const cashColumns = [
    { key: 'id', label: 'ID' },
    { key: 'date', label: 'Date', format: (value) => formatDate(value) },
    { key: 'description', label: 'Description' },
    { key: 'amount', label: 'Montant', format: (value) => formatCurrency(value) },
];

const ventilationColumns = [
    { key: 'id', label: 'ID' },
    { key: 'date', label: 'Date', format: (value) => formatDate(value) },
    { key: 'description', label: 'Description' },
    { key: 'amount', label: 'Montant', format: (value) => formatCurrency(value) },
];

const filteredContracts = computed(() => {
    return contracts.value.filter(contract => {
        const matchesTenant = !filters.value.tenant || contract.tenant.id == filters.value.tenant;
        const matchesProperty = !filters.value.property || contract.property.id == filters.value.property;
        const matchesDateFrom = !filters.value.date_from || new Date(contract.start_date) >= new Date(filters.value.date_from);
        const matchesDateTo = !filters.value.date_to || new Date(contract.end_date) <= new Date(filters.value.date_to);
        return matchesTenant && matchesProperty && matchesDateFrom && matchesDateTo;
    });
});

const filteredPayments = computed(() => {
    return payments.value.filter(payment => {
        const matchesTenant = !filters.value.tenant || payment.contract.tenant.id == filters.value.tenant;
        const matchesProperty = !filters.value.property || payment.contract.property.id == filters.value.property;
        const matchesDateFrom = !filters.value.date_from || new Date(payment.payment_date) >= new Date(filters.value.date_from);
        const matchesDateTo = !filters.value.date_to || new Date(payment.payment_date) <= new Date(filters.value.date_to);
        return matchesTenant && matchesProperty && matchesDateFrom && matchesDateTo;
    });
});

const filteredExpenses = computed(() => {
    return expenses.value.filter(expense => {
        const matchesProperty = !filters.value.property || expense.property.id == filters.value.property;
        const matchesDateFrom = !filters.value.date_from || new Date(expense.expense_date) >= new Date(filters.value.date_from);
        const matchesDateTo = !filters.value.date_to || new Date(expense.expense_date) <= new Date(filters.value.date_to);
        return matchesProperty && matchesDateFrom && matchesDateTo;
    });
});

const filteredCashes = computed(() => {
    return cashes.value.filter(cash => {
        const matchesDateFrom = !filters.value.date_from || new Date(cash.date) >= new Date(filters.value.date_from);
        const matchesDateTo = !filters.value.date_to || new Date(cash.date) <= new Date(filters.value.date_to);
        return matchesDateFrom && matchesDateTo;
    });
});

const filteredVentilations = computed(() => {
    return ventilations.value.filter(ventilation => {
        const matchesDateFrom = !filters.value.date_from || new Date(ventilation.date) >= new Date(filters.value.date_from);
        const matchesDateTo = !filters.value.date_to || new Date(ventilation.date) <= new Date(filters.value.date_to);
        return matchesDateFrom && matchesDateTo;
    });
});

const applyFilters = () => {
    console.log('Filtres appliqués:', filters.value);
};

const resetFilters = () => {
    filters.value = {
        tenant: "",
        property: "",
        date_from: "",
        date_to: "",
    };
};

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
