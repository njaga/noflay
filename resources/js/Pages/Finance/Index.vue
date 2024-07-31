<template>
    <AppLayout title="Finance">
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Boutons de raccourcis
                <div class="flex justify-end mb-8 space-x-4">
                    <Link
                        :href="route('expenses.create')"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                    >
                        <span class="flex items-center">
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                ></path>
                            </svg>
                            Ajouter une Dépense
                        </span>
                    </Link>
                    <Link
                        :href="route('payments.create')"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out"
                    >
                        <span class="flex items-center">
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                ></path>
                            </svg>
                            Ajouter un Paiement
                        </span>
                    </Link>
                </div>
                -->

                <!-- Statistiques -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                >
                    <StatCard
                        title="Total des Cautions"
                        :value="totalCautions"
                        icon="lock"
                        color="bg-indigo-500"
                        :useKFormat="true"
                        :valuePrefix="CURRENCY_SYMBOL"
                    />
                    <StatCard
                        title="Total des Commissions"
                        :value="totalCommissions"
                        icon="dollar-sign"
                        color="bg-green-500"
                        :useKFormat="true"
                        :valuePrefix="CURRENCY_SYMBOL"
                    />
                    <StatCard
                        title="Total des Loyers"
                        :value="totalRent"
                        icon="home"
                        color="bg-purple-500"
                        :useKFormat="true"
                        :valuePrefix="CURRENCY_SYMBOL"
                    />
                    <StatCard
                        title="Total des Dépenses"
                        :value="totalExpenses"
                        icon="shopping-cart"
                        color="bg-red-500"
                        :useKFormat="true"
                        :valuePrefix="CURRENCY_SYMBOL"
                    />
                </div>

                <div
                    class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl shadow-lg p-8 mb-8"
                >
                    <h3
                        class="text-2xl font-bold text-indigo-600 mb-6 flex items-center"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8 mr-2 text-indigo-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            />
                        </svg>
                        Comparaison des Paiements
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="bg-white p-6 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg"
                        >
                            <h4
                                class="text-lg font-semibold text-gray-700 mb-2 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-indigo-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 19l-7-7 7-7"
                                    />
                                </svg>
                                Mois Précédent
                            </h4>
                            <p class="text-3xl font-bold text-indigo-600">
                                {{ formatCurrency(previousMonthPayments) }}
                            </p>
                        </div>
                        <div
                            class="bg-white p-6 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg"
                        >
                            <h4
                                class="text-lg font-semibold text-gray-700 mb-2 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-green-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                                Mois en Cours
                            </h4>
                            <p class="text-3xl font-bold text-green-600">
                                {{ formatCurrency(currentMonthPayments) }}
                            </p>
                        </div>
                        <div
                            class="bg-white p-6 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg"
                        >
                            <h4
                                class="text-lg font-semibold text-gray-700 mb-2 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-indigo-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                    />
                                </svg>
                                Différence
                            </h4>
                            <p
                                :class="{
                                    'text-red-600': paymentsDifference < 0,
                                    'text-indigo-600': paymentsDifference >= 0,
                                }"
                                class="text-3xl font-bold mb-2"
                            >
                                {{ formatCurrency(paymentsDifference) }}
                            </p>
                            <div class="relative pt-1">
                                <div
                                    class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200"
                                >
                                    <div
                                        :style="{
                                            width: differencePercentage + '%',
                                        }"
                                        :class="{
                                            'bg-red-500':
                                                paymentsDifference < 0,
                                            'bg-indigo-500':
                                                paymentsDifference >= 0,
                                        }"
                                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-500"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        Filtres avancés
                    </h2>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                    >
                        <div>
                            <label
                                for="filter-tenant"
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Locataire</label
                            >
                            <select
                                id="filter-tenant"
                                v-model="filters.tenant"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Tous les locataires</option>
                                <option
                                    v-for="tenant in tenants"
                                    :key="tenant.id"
                                    :value="tenant.id"
                                >
                                    {{ tenant.first_name }}
                                    {{ tenant.last_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="filter-property"
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Propriété</label
                            >
                            <select
                                id="filter-property"
                                v-model="filters.property"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Toutes les propriétés</option>
                                <option
                                    v-for="property in properties"
                                    :key="property.id"
                                    :value="property.id"
                                >
                                    {{ property.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="filter-date-from"
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Date de Début</label
                            >
                            <input
                                type="date"
                                id="filter-date-from"
                                v-model="filters.date_from"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <label
                                for="filter-date-to"
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Date de Fin</label
                            >
                            <input
                                type="date"
                                id="filter-date-to"
                                v-model="filters.date_to"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            />
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
                            <DataSection
                                title="Cautions"
                                :data="filteredContracts"
                                :columns="cautionColumns"
                            />
                        </template>
                        <template #payments>
                            <DataSection
                                title="Paiements"
                                :data="filteredPayments"
                                :columns="paymentColumns"
                            />
                        </template>
                        <template #expenses>
                            <DataSection
                                title="Dépenses"
                                :data="filteredExpenses"
                                :columns="expenseColumns"
                            />
                        </template>
                        <template #commissions>
                            <DataSection
                                title="Commissions"
                                :data="filteredContracts"
                                :columns="commissionColumns"
                            />
                        </template>
                        <template #cash>
                            <DataSection
                                title="Grand Livre Caisse"
                                :data="filteredCashes"
                                :columns="cashColumns"
                            />
                        </template>

                        <template #ventilation>
                            <DataSection
                                title="Ventilation Caisse"
                                :data="filteredVentilations"
                                :columns="ventilationColumns"
                            />
                        </template>
                    </NavigationTabs>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import StatCard from "@/Components/StatCard.vue";
import DataSection from "@/Components/DataSection.vue";
import NavigationTabs from "@/Components/NavigationTabs.vue";

const CURRENCY_SYMBOL = "F CFA";
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
const paymentsDifference = computed(
    () => currentMonthPayments.value - previousMonthPayments.value
);

const differencePercentage = computed(() => {
    const percentage =
        (Math.abs(paymentsDifference.value) / previousMonthPayments.value) *
        100;
    return Math.min(percentage, 100); // Limiter à 100% pour l'affichage de la barre
});

function formatCurrency(value) {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
        currencyDisplay: "symbol",
    }).format(value);
}

function formatDate(dateString) {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
}

function formatLargeNumber(value) {
    if (value >= 1000000) {
        return (value / 1000000).toFixed(1) + "M " + CURRENCY_SYMBOL;
    } else if (value >= 1000) {
        return (value / 1000).toFixed(1) + "K " + CURRENCY_SYMBOL;
    }
    return value.toString() + " " + CURRENCY_SYMBOL;
}

const filters = ref({
    tenant: "",
    property: "",
    date_from: "",
    date_to: "",
});

const activeTab = ref("cautions");
const tabs = [
    { name: "Cautions", id: "cautions" },
    { name: "Paiements", id: "payments" },
    { name: "Dépenses", id: "expenses" },
    { name: "Commissions", id: "commissions" },
    { name: "Grand Livre Caisse", id: "cash" },
    { name: "Ventilation Caisse", id: "ventilation" },
];

const cautionColumns = [
    { key: "id", label: "ID" },
    {
        key: "tenant",
        label: "Locataire",
        format: (value) =>
            value ? `${value.first_name} ${value.last_name}` : "N/A",
    },
    {
        key: "property",
        label: "Propriété",
        format: (value) => (value ? value.name : "N/A"),
    },
    {
        key: "caution_amount",
        label: "Montant",
        format: (value) => formatCurrency(value),
    },
];

const paymentColumns = [
    { key: "id", label: "ID" },
    {
        key: "contract.tenant",
        label: "Locataire",
        format: (value) =>
            value ? `${value.first_name} ${value.last_name}` : "N/A",
    },
    {
        key: "contract.property",
        label: "Propriété",
        format: (value) => (value ? value.name : "N/A"),
    },
    {
        key: "amount",
        label: "Montant",
        format: (value) => formatCurrency(value),
    },
    {
        key: "payment_date",
        label: "Date de Paiement",
        format: (value) => formatDate(value),
    },
];

const expenseColumns = [
    { key: "id", label: "ID" },
    { key: "description", label: "Description" },
    {
        key: "property",
        label: "Propriété",
        format: (value) => (value ? value.name : "N/A"),
    },
    {
        key: "amount",
        label: "Montant",
        format: (value) => formatCurrency(value),
    },
    {
        key: "expense_date",
        label: "Date de Dépense",
        format: (value) => formatDate(value),
    },
];

const commissionColumns = [
    { key: "id", label: "ID" },
    {
        key: "tenant",
        label: "Locataire",
        format: (value) =>
            value ? `${value.first_name} ${value.last_name}` : "N/A",
    },
    {
        key: "property",
        label: "Propriété",
        format: (value) => (value ? value.name : "N/A"),
    },
    {
        key: "commission_amount",
        label: "Montant",
        format: (value) => formatCurrency(value),
    },
];

const cashColumns = [
    { key: "id", label: "ID" },
    { key: "date", label: "Date", format: (value) => formatDate(value) },
    { key: "description", label: "Description" },
    {
        key: "amount",
        label: "Montant",
        format: (value) => formatCurrency(value),
    },
    {
        key: "type",
        label: "Type",
        format: (value) => value.toUpperCase(),
    },
];

const ventilationColumns = [
    { key: "id", label: "ID" },
    {
        key: "category",
        label: "Catégorie",
        format: (value) => value.charAt(0).toUpperCase() + value.slice(1),
    },
    {
        key: "total_amount",
        label: "Montant Total",
        format: (value) => formatCurrency(value),
    },
    {
        key: "transaction_count",
        label: "Nombre de Transactions",
    },
];

const filteredContracts = computed(() => {
    return contracts.value.filter((contract) => {
        const matchesTenant =
            !filters.value.tenant ||
            (contract.tenant && contract.tenant.id == filters.value.tenant);
        const matchesProperty =
            !filters.value.property ||
            (contract.property &&
                contract.property.id == filters.value.property);
        const matchesDateFrom =
            !filters.value.date_from ||
            new Date(contract.start_date) >= new Date(filters.value.date_from);
        const matchesDateTo =
            !filters.value.date_to ||
            new Date(contract.end_date) <= new Date(filters.value.date_to);
        return (
            matchesTenant && matchesProperty && matchesDateFrom && matchesDateTo
        );
    });
});

const filteredPayments = computed(() => {
    return payments.value.filter((payment) => {
        const matchesTenant =
            !filters.value.tenant ||
            (payment.contract.tenant &&
                payment.contract.tenant.id == filters.value.tenant);
        const matchesProperty =
            !filters.value.property ||
            (payment.contract.property &&
                payment.contract.property.id == filters.value.property);
        const matchesDateFrom =
            !filters.value.date_from ||
            new Date(payment.payment_date) >= new Date(filters.value.date_from);
        const matchesDateTo =
            !filters.value.date_to ||
            new Date(payment.payment_date) <= new Date(filters.value.date_to);
        return (
            matchesTenant && matchesProperty && matchesDateFrom && matchesDateTo
        );
    });
});

const filteredExpenses = computed(() => {
    return expenses.value.filter((expense) => {
        const matchesProperty =
            !filters.value.property ||
            (expense.property && expense.property.id == filters.value.property);
        const matchesDateFrom =
            !filters.value.date_from ||
            new Date(expense.expense_date) >= new Date(filters.value.date_from);
        const matchesDateTo =
            !filters.value.date_to ||
            new Date(expense.expense_date) <= new Date(filters.value.date_to);
        return matchesProperty && matchesDateFrom && matchesDateTo;
    });
});

const filteredCashes = computed(() => {
    return cashes.value.filter((cash) => {
        const matchesDateFrom =
            !filters.value.date_from ||
            new Date(cash.date) >= new Date(filters.value.date_from);
        const matchesDateTo =
            !filters.value.date_to ||
            new Date(cash.date) <= new Date(filters.value.date_to);
        return matchesDateFrom && matchesDateTo;
    });
});

const filteredVentilations = computed(() => {
    // Nous allons simuler une ventilation basée sur les transactions du Grand Livre Caisse
    const ventilationMap = new Map();
    filteredCashes.value.forEach((cash) => {
        if (!ventilationMap.has(cash.type)) {
            ventilationMap.set(cash.type, {
                total_amount: 0,
                transaction_count: 0,
            });
        }
        const category = ventilationMap.get(cash.type);
        category.total_amount += cash.amount;
        category.transaction_count += 1;
    });

    return Array.from(ventilationMap, ([category, data]) => ({
        id: category,
        category,
        ...data,
    }));
});

const applyFilters = () => {
    console.log("Filtres appliqués:", filters.value);
};

const resetFilters = () => {
    filters.value = {
        tenant: "",
        property: "",
        date_from: "",
        date_to: "",
    };
};
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200;
}

.btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold text-sm shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200;
}
</style>
