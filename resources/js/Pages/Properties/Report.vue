<template>
    <AppLayout :title="`${property.name} - Rapport`">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-100">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold text-indigo-800 mb-8">
                    Rapport de la propriété : {{ property.name }}
                </h1>

                <!-- Vue d'ensemble -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                >
                    <StatCard
                        title="Cautions"
                        :value="Number(cautionAmounts)"
                        icon="shield-alt"
                        color="bg-blue-500"
                    />
                    <StatCard
                        title="Loyers"
                        :value="Number(rentAmounts)"
                        icon="home"
                        color="bg-green-500"
                    />
                    <StatCard
                        title="Paiements reçus"
                        :value="Number(totalPayments)"
                        icon="money-bill-wave"
                        color="bg-yellow-500"
                    />
                    <StatCard
                        title="Commissions"
                        :value="Number(commissionAmounts)"
                        icon="percentage"
                        color="bg-purple-500"
                    />
                </div>

                <!-- Informations sur la propriété -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-6 py-4 bg-indigo-600">
                        <h3 class="text-lg font-semibold text-white">
                            Informations sur la propriété
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Nom
                                </p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ property.name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Type de propriété
                                </p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ property.property_type }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Adresse
                                </p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ property.address }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Propriétaire
                                </p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{
                                        property.landlord
                                            ? `${property.landlord.first_name} ${property.landlord.last_name}`
                                            : "N/A"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <ChartCard title="Revenus mensuels">
                        <BarChart :chart-data="revenueChartData" />
                    </ChartCard>
                    <ChartCard title="Répartition des dépenses">
                        <DoughnutChart :chart-data="expenseChartData" />
                    </ChartCard>
                </div>

<!-- Historique des transactions -->
<div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-4 bg-indigo-600">
                <h3 class="text-lg font-semibold text-white">Historique des transactions</h3>
            </div>
            <div class="p-6">
                <TabGroup>
                    <TabList class="flex space-x-1 rounded-xl bg-blue-900/20 p-1">
                        <Tab v-for="category in ['Paiements', 'Versements', 'Dépenses']" as="template" :key="category" v-slot="{ selected }">
                            <button
                                :class="[
                                    'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-700',
                                    'ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                    selected
                                        ? 'bg-white shadow'
                                        : 'text-blue-100 hover:bg-white/[0.12] hover:text-white'
                                ]"
                            >
                                {{ category }}
                            </button>
                        </Tab>
                    </TabList>
                    <TabPanels class="mt-2">
                        <TabPanel>
                            <TransactionTable :transactions="payments" type="payment" />
                        </TabPanel>
                        <TabPanel>
                            <TransactionTable :transactions="payouts" type="payout" />
                        </TabPanel>
                        <TabPanel>
                            <TransactionTable :transactions="expenses" type="expense" />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import StatCard from "@/Components/StatCard.vue";
import ChartCard from "@/Components/ChartCard.vue";
import BarChart from "@/Components/Charts/BarChart.vue";
import DoughnutChart from "@/Components/Charts/DoughnutChart.vue";
import TransactionTable from "@/Components/TransactionTable.vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";

const { props } = usePage();
const {
    property,
    cautionAmounts,
    rentAmounts,
    totalPayments,
    commissionAmounts,
    revenueData,
    payments,
    payouts,
    expenses
} = props;

// Données pour le graphique des revenus
const revenueChartData = computed(() => ({
    labels: [
        "Jan",
        "Fév",
        "Mar",
        "Avr",
        "Mai",
        "Jun",
        "Jul",
        "Aoû",
        "Sep",
        "Oct",
        "Nov",
        "Déc",
    ],
    datasets: [
        {
            label: "Revenus mensuels",
            data: revenueData,
            backgroundColor: "rgba(75, 192, 192, 0.6)",
        },
    ],
}));

// Données pour le graphique des dépenses
const expenseChartData = computed(() => ({
    labels: ["Entretien", "Réparations", "Taxes", "Assurance", "Autres"],
    datasets: [
        {
            data: [3000, 5000, 2000, 1000, 500],
            backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#4BC0C0",
                "#9966FF",
            ],
        },
    ],
}));

// Fonction pour formater la devise
const formatCurrency = (value) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(value);
};
</script>
