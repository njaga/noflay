<template>
    <AppLayout title="Gestion des Dépenses">
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold mb-8 text-gray-900">Dépenses</h2>

                <!-- Summary Cards -->
                <SummaryCards :summaryStats="summaryStats" />

                <!-- Boutons de raccourcis -->
                <div class="flex justify-end mb-8">
                    <Link :href="route('expenses.create')" class="btn-primary mr-2">Ajouter une Dépense</Link>
                </div>

                <!-- Filtres -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Filtres avancés</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="filter-property" class="block text-sm font-medium text-gray-700 mb-1">Propriété</label>
                            <select id="filter-property" v-model="filters.property" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Toutes les propriétés</option>
                                <option v-for="property in properties" :key="property.id" :value="property.id">
                                    {{ property.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-date-from" class="block text-sm font-medium text-gray-700 mb-1">Date de Début</label>
                            <input type="date" id="filter-date-from" v-model="filters.date_from" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label for="filter-date-to" class="block text-sm font-medium text-gray-700 mb-1">Date de Fin</label>
                            <input type="date" id="filter-date-to" v-model="filters.date_to" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
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
                <div class="space-y-8">
                    <DataSection title="Liste des Dépenses" :data="filteredExpenses" :columns="expenseColumns" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataSection from '@/Components/DataSection.vue';
import SummaryCards from '@/Components/LandlordPayouts/SummaryCards.vue';
import { Link } from '@inertiajs/vue3';

const { props } = usePage();
const expenses = ref(Array.isArray(props.expenses) ? props.expenses : []);
const properties = ref(props.properties || []);

const filters = ref({
    property: "",
    date_from: "",
    date_to: "",
});

const expenseColumns = [
    { key: 'id', label: 'ID' },
    { key: 'property.name', label: 'Propriété' },
    { key: 'description', label: 'Description' },
    { key: 'amount', label: 'Montant', format: value => formatCurrency(value) },
    { key: 'expense_date', label: 'Date de Dépense', format: value => formatDate(value) },
];

const filteredExpenses = computed(() => {
    return expenses.value.filter(expense => {
        const matchesProperty = !filters.value.property || expense.property.id == filters.value.property;
        const matchesDateFrom = !filters.value.date_from || new Date(expense.expense_date) >= new Date(filters.value.date_from);
        const matchesDateTo = !filters.value.date_to || new Date(expense.expense_date) <= new Date(filters.value.date_to);
        return matchesProperty && matchesDateFrom && matchesDateTo;
    });
});

const summaryStats = computed(() => [
    {
        title: "Total des dépenses",
        value: formatCurrency(filteredExpenses.value.reduce((sum, expense) => sum + expense.amount, 0)),
        description: "Montant total des dépenses pour la période sélectionnée"
    },
    {
        title: "Nombre de dépenses",
        value: filteredExpenses.value.length.toString(),
        description: "Nombre total de dépenses enregistrées"
    },
    {
        title: "Moyenne des dépenses",
        value: formatCurrency(filteredExpenses.value.length > 0
            ? filteredExpenses.value.reduce((sum, expense) => sum + expense.amount, 0) / filteredExpenses.value.length
            : 0),
        description: "Montant moyen des dépenses pour la période"
    }
]);

const applyFilters = () => {
    console.log('Filtres appliqués:', filters.value);
};

const resetFilters = () => {
    filters.value = {
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
