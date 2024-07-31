<template>
    <AppLayout title="Ventilation des Transactions">
        <div class="ventilation-container">
            <!-- En-tête avec résumé -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                    Ventilation des Transactions
                </h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-100 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-blue-800">
                            Total des Transactions
                        </h2>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ formatCurrency(totalTransactions) }}
                        </p>
                    </div>
                    <div class="bg-green-100 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-green-800">
                            Revenus
                        </h2>
                        <p class="text-3xl font-bold text-green-600">
                            {{ formatCurrency(totalRevenue) }}
                        </p>
                    </div>
                    <div class="bg-red-100 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-red-800">
                            Dépenses
                        </h2>
                        <p class="text-3xl font-bold text-red-600">
                            {{ formatCurrency(totalExpenses) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Filtres</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="dateRange" class="block text-sm font-medium text-gray-700">Période</label>
                        <select v-model="filters.dateRange" id="dateRange" @change="updateDateRange"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="all">Toutes les périodes</option>
                            <option value="today">Aujourd'hui</option>
                            <option value="thisWeek">Cette semaine</option>
                            <option value="thisMonth">Ce mois</option>
                            <option value="thisYear">Cette année</option>
                            <option value="custom">Personnalisé</option>
                        </select>
                    </div>
                    <div v-if="filters.dateRange === 'custom'">
                        <label for="startDate" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" v-model="filters.startDate" id="startDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div v-if="filters.dateRange === 'custom'">
                        <label for="endDate" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" v-model="filters.endDate" id="endDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>
                <div class="mt-4">
                    <button @click="applyFilters"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
                        Appliquer les filtres
                    </button>
                </div>
            </div>


            <!-- Graphiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Répartition par Type
                    </h2>
                    <div style="height: 300px">
                        <PieChart :chart-data="typeChartData" :options="pieChartOptions" />
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Évolution Mensuelle
                    </h2>
                    <div style="height: 300px">
                        <LineChart :chart-data="monthlyChartData" :options="lineChartOptions" />
                    </div>
                </div>
            </div>

            <!-- Tableaux de ventilation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Ventilation par Bailleur
                    </h2>
                    <div v-if="ventilationByLandlordArray.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bailleur
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Transactions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="landlord in ventilationByLandlordArray" :key="landlord.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ landlord.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{
                                            formatCurrency(
                                                landlord.total_amount
                                            )
                                        }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ landlord.transaction_count }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center py-4 text-gray-500">
                        Aucune donnée disponible pour la ventilation par
                        bailleur.
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Ventilation par Propriété
                    </h2>
                    <div v-if="ventilationByPropertyArray.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Propriété
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Transactions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="property in ventilationByPropertyArray" :key="property.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ property.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{
                                            formatCurrency(
                                                property.total_amount
                                            )
                                        }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ property.transaction_count }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center py-4 text-gray-500">
                        Aucune donnée disponible pour la ventilation par
                        propriété.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PieChart from "@/Components/Charts/PieChart.vue";
import LineChart from "@/Components/Charts/LineChart.vue";

const page = usePage();
const props = defineProps({
    ventilationByLandlord: {
        type: Object,
        default: () => ({}),
    },
    ventilationByProperty: {
        type: Object,
        default: () => ({}),
    },
    ventilationByType: {
        type: Array,
        default: () => [],
    },
});

const filters = reactive({
  dateRange: 'all',
  startDate: '',
  endDate: '',
});

const updateDateRange = () => {
  const today = new Date();
  switch (filters.dateRange) {
    case 'today':
      filters.startDate = today.toISOString().split('T')[0];
      filters.endDate = today.toISOString().split('T')[0];
      break;
    case 'thisWeek':
      const firstDayOfWeek = new Date(today.setDate(today.getDate() - today.getDay()));
      filters.startDate = firstDayOfWeek.toISOString().split('T')[0];
      filters.endDate = new Date().toISOString().split('T')[0];
      break;
    case 'thisMonth':
      filters.startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
      filters.endDate = new Date().toISOString().split('T')[0];
      break;
    case 'thisYear':
      filters.startDate = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
      filters.endDate = new Date().toISOString().split('T')[0];
      break;
    case 'custom':
      // Les dates seront définies manuellement par l'utilisateur
      break;
    default:
      filters.startDate = '';
      filters.endDate = '';
  }
};

const applyFilters = () => {
  router.get('/transactions/ventilation', filters, {
    preserveState: true,
    preserveScroll: true,
    only: ['ventilationByLandlord', 'ventilationByProperty', 'ventilationByType'],
  });
};

const ventilationByLandlordArray = computed(() => {
    return props.ventilationByLandlord.data
        .filter(
            (landlord) =>
                landlord != null &&
                landlord.first_name &&
                landlord.last_name &&
                (parseFloat(landlord.transactions_sum_amount) !== 0 ||
                    parseInt(landlord.transactions_count) !== 0)
        )
        .map((landlord) => ({
            id: landlord.id,
            name: `${landlord.first_name} ${landlord.last_name}`,
            total_amount: parseFloat(landlord.transactions_sum_amount) || 0,
            transaction_count: parseInt(landlord.transactions_count) || 0,
        }));
});

const ventilationByPropertyArray = computed(() => {
    return props.ventilationByProperty.data
        .filter(
            (property) =>
                property != null &&
                property.name &&
                (parseFloat(property.transactions_sum_amount) !== 0 ||
                    parseInt(property.transactions_count) !== 0)
        )
        .map((property) => ({
            id: property.id,
            name: property.name,
            total_amount: parseFloat(property.transactions_sum_amount) || 0,
            transaction_count: parseInt(property.transactions_count) || 0,
        }));
});

const translateTransactionType = (type) => {
    const translations = {
        PAYMENT: "Paiement",
        EXPENSE: "Dépense",
        DEPOSIT: "Caution",
        REFUND: "Remboursement",
        LANDLORD_PAYOUT: "Versement au bailleur",
    };
    return translations[type] || type;
};

const ventilationByType = computed(() => {
    return props.ventilationByType
        .filter((item) => parseFloat(item.total_amount) !== 0)
        .map((item) => ({
            ...item,
            type: translateTransactionType(item.type),
            total_amount: parseFloat(item.total_amount) || 0,
        }));
});

const totalTransactions = computed(() => {
    return ventilationByType.value.reduce(
        (total, item) => total + Math.abs(item.total_amount),
        0
    );
});

const totalRevenue = computed(() => {
    return ventilationByType.value
        .filter((item) => ["Paiement", "Caution"].includes(item.type))
        .reduce((total, item) => total + item.total_amount, 0);
});

const totalExpenses = computed(() => {
    return ventilationByType.value
        .filter((item) =>
            ["Dépense", "Versement au bailleur"].includes(item.type)
        )
        .reduce((total, item) => total + Math.abs(item.total_amount), 0);
});

const typeChartData = computed(() => {
    return {
        labels: ventilationByType.value.map((item) => item.type),
        datasets: [
            {
                data: ventilationByType.value.map((item) =>
                    Math.abs(item.total_amount)
                ),
                backgroundColor: [
                    "#4CAF50",
                    "#2196F3",
                    "#FFC107",
                    "#F44336",
                    "#9C27B0",
                ],
            },
        ],
    };
});

const monthlyChartData = computed(() => {
    // Simuler des données mensuelles (à remplacer par de vraies données)
    const months = [
        "Jan",
        "Fév",
        "Mar",
        "Avr",
        "Mai",
        "Juin",
        "Juil",
        "Août",
        "Sep",
        "Oct",
        "Nov",
        "Déc",
    ];
    const revenues = months.map(() => Math.floor(Math.random() * 10000));
    const expenses = months.map(() => Math.floor(Math.random() * 8000));

    return {
        labels: months,
        datasets: [
            {
                label: "Revenus",
                data: revenues,
                borderColor: "#4CAF50",
                backgroundColor: "rgba(76, 175, 80, 0.1)",
            },
            {
                label: "Dépenses",
                data: expenses,
                borderColor: "#F44336",
                backgroundColor: "rgba(244, 67, 54, 0.1)",
            },
        ],
    };
});

const pieChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
        },
        tooltip: {
            callbacks: {
                label: function (context) {
                    let label = context.label || "";
                    if (label) {
                        label += ": ";
                    }
                    if (context.parsed !== null) {
                        label += new Intl.NumberFormat("fr-FR", {
                            style: "currency",
                            currency: "XOF",
                        }).format(context.parsed);
                    }
                    return label;
                },
            },
        },
    },
};

const lineChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "top",
        },
        tooltip: {
            mode: "index",
            intersect: false,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                callback: function (value, index, values) {
                    return new Intl.NumberFormat("fr-FR", {
                        style: "currency",
                        currency: "XOF",
                    }).format(value);
                },
            },
        },
    },
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(value || 0);
};

onMounted(() => {
  // Initialiser les filtres avec les valeurs de l'URL si elles existent
  const urlParams = new URLSearchParams(window.location.search);
  filters.dateRange = urlParams.get('dateRange') || 'all';
  filters.startDate = urlParams.get('startDate') || '';
  filters.endDate = urlParams.get('endDate') || '';

  if (filters.dateRange !== 'custom') {
    updateDateRange();
  }
});

</script>

<style scoped>
.ventilation-container {
    @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8;
}

.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}

table {
    @apply min-w-full divide-y divide-gray-200;
}

th {
    @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
}

td {
    @apply px-6 py-4 whitespace-nowrap text-sm text-gray-500;
}

.card {
    @apply bg-white shadow rounded-lg p-6 mb-6;
}

.card-title {
    @apply text-xl font-semibold text-gray-800 mb-4;
}

.filter-input {
    @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50;
}

.btn-primary {
    @apply bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out;
}

.empty-state {
    @apply text-center py-4 text-gray-500;
}
</style>
