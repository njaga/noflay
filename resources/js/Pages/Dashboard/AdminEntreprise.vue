<template>
    <AppLayout :title="`Tableau de bord - Admin ${company.name}`">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-3xl font-bold mb-8 text-gray-800">
                            Tableau de bord
                        </h2>

                        <!-- Onglets pour la navigation -->
                        <div class="mb-8">
                            <nav class="flex space-x-4" aria-label="Tabs">
                                <button
                                    v-for="tab in tabs"
                                    :key="tab.name"
                                    @click="activeTab = tab.name"
                                    :class="tabClass(tab.name)"
                                >
                                    {{ tab.label }}
                                </button>
                            </nav>
                        </div>

                        <!-- Contenu des onglets -->
                        <div v-if="activeTab === 'apercu'">
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                            >
                                <StatCard
                                    v-for="stat in overviewStats"
                                    :key="stat.title"
                                    :title="stat.title"
                                    :value="stat.value"
                                    :icon="stat.icon"
                                    :color="stat.color"
                                    :link="stat.link"
                                />
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <ChartCard title="Évolution des Contrats">
                                    <LineChart
                                        :chart-data="contractChartData"
                                    />
                                </ChartCard>
                                <ChartCard title="Répartition des Propriétés">
                                    <DoughnutChart
                                        :chart-data="propertyTypesChartData"
                                    />
                                </ChartCard>
                            </div>
                        </div>

                        <div v-if="activeTab === 'utilisateurs'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <ChartCard title="Évolution des Utilisateurs">
                                    <AreaChart
                                        :chart-data="userGrowthChartData"
                                    />
                                </ChartCard>
                                <ChartCard title="Évolution des Bailleurs">
                                    <BarChart :chart-data="landlordChartData" />
                                </ChartCard>
                                <ChartCard title="Évolution des Locataires">
                                    <LineChart :chart-data="tenantChartData" />
                                </ChartCard>
                                <ChartCard title="Répartition des Utilisateurs">
                                    <PolarAreaChart
                                        :chart-data="userDistributionChartData"
                                    />
                                </ChartCard>
                            </div>
                        </div>

                        <div v-if="activeTab === 'finances'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <ChartCard title="Revenus Mensuels">
                                    <BarChart :chart-data="revenueChartData" />
                                </ChartCard>
                                <ChartCard title="Commissions Reçues">
                                    <LineChart
                                        :chart-data="commissionChartData"
                                    />
                                </ChartCard>
                                <ChartCard title="Retards de Paiement">
                                    <ScatterChart
                                        :chart-data="latePaymentChartData"
                                    />
                                </ChartCard>
                                <ChartCard title="Évolution des Dépenses">
                                    <AreaChart :chart-data="expenseChartData" />
                                </ChartCard>
                            </div>
                        </div>

                        <div v-if="activeTab === 'proprietes'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <ChartCard title="Taux d'Occupation">
                                    <LineChart
                                        :chart-data="occupancyRateChartData"
                                    />
                                </ChartCard>
                                <ChartCard title="Répartition des Dépenses">
                                    <RadarChart
                                        :chart-data="
                                            expenseDistributionChartData
                                        "
                                    />
                                </ChartCard>
                            </div>
                            <div class="mt-8">
                                <h3 class="text-xl font-semibold mb-4">
                                    Propriétés Récentes
                                </h3>
                                <PropertyTable :properties="recentProperties" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import StatCard from "@/Components/StatCard.vue";
import ChartCard from "@/Components/ChartCard.vue";
import LineChart from "@/Components/Charts/LineChart.vue";
import BarChart from "@/Components/Charts/BarChart.vue";
import DoughnutChart from "@/Components/Charts/DoughnutChart.vue";
import AreaChart from "@/Components/Charts/AreaChart.vue";
import ScatterChart from "@/Components/Charts/ScatterChart.vue";
import RadarChart from "@/Components/Charts/RadarChart.vue";
import PolarAreaChart from "@/Components/Charts/PolarAreaChart.vue";
import PropertyTable from "@/Components/PropertyTable.vue";

const { props } = usePage();
const activeTab = ref("apercu");

const company = ref(props.company || {});
const users = ref(props.users || []);
const contracts = ref(props.contracts || []);
const properties = ref(props.properties || []);
const tenants = ref(props.tenants || []);

const tabs = [
    { name: "apercu", label: "Aperçu" },
    { name: "utilisateurs", label: "Utilisateurs" },
    { name: "finances", label: "Finances" },
    { name: "proprietes", label: "Propriétés" },
];

const overviewStats = computed(() => [
    {
        title: "Utilisateurs",
        value: users.value.length,
        icon: "users",
        color: "bg-blue-500",
        link: "/users",
    },
    {
        title: "Contrats",
        value: contracts.value.length,
        icon: "file-text",
        color: "bg-green-500",
        link: "/contracts",
    },
    {
        title: "Propriétés",
        value: properties.value.length,
        icon: "home",
        color: "bg-yellow-500",
        link: "/properties",
    },
    {
        title: "Locataires",
        value: tenants.value.length,
        icon: "user-check",
        color: "bg-purple-500",
        link: "/tenants",
    },
]);

const recentProperties = computed(() => properties.value.slice(0, 5));

const contractChartData = computed(() => ({
    labels: props.contractStats.labels,
    datasets: [
        {
            label: "Nombre de Contrats",
            data: props.contractStats.data,
            borderColor: "rgba(75, 192, 192, 1)",
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            tension: 0.1,
            fill: true,
        },
    ],
}));

const propertyTypesChartData = computed(() => ({
    labels: props.propertyTypes.labels,
    datasets: [
        {
            data: props.propertyTypes.data,
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
            label: "Revenus",
            data: props.revenueStats.data,
            backgroundColor: "rgba(54, 162, 235, 0.5)",
        },
    ],
}));

const occupancyRateChartData = computed(() => ({
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
            label: "Taux d'Occupation",
            data: props.occupancyRateStats.data,
            borderColor: "rgba(255, 99, 132, 1)",
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            tension: 0.1,
            fill: true,
        },
    ],
}));

const userGrowthChartData = computed(() => ({
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
            label: "Nouveaux Utilisateurs",
            data: props.userGrowthStats.data,
            borderColor: "rgba(75, 192, 192, 1)",
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            tension: 0.1,
            fill: true,
        },
    ],
}));

const landlordChartData = computed(() => ({
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
            label: "Nouveaux Bailleurs",
            data: props.landlordStats.data,
            backgroundColor: "rgba(153, 102, 255, 0.5)",
            borderColor: "rgba(153, 102, 255, 1)",
            borderWidth: 1,
        },
    ],
}));

const tenantChartData = computed(() => ({
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
            label: "Nouveaux Locataires",
            data: props.tenantStats.data,
            borderColor: "rgba(255, 159, 64, 1)",
            backgroundColor: "rgba(255, 159, 64, 0.2)",
            tension: 0.1,
            fill: true,
        },
    ],
}));

const commissionChartData = computed(() => ({
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
            label: "Commissions Reçues",
            data: props.commissionStats.data,
            borderColor: "rgba(255, 206, 86, 1)",
            backgroundColor: "rgba(255, 206, 86, 0.2)",
            tension: 0.1,
            fill: true,
        },
    ],
}));

const latePaymentChartData = computed(() => ({
    datasets: [
        {
            label: "Retards de Paiement",
            data: props.latePaymentStats.data.map((value, index) => ({
                x: index + 1,
                y: value,
                r: Math.sqrt(value) * 2, // La taille du point dépend de la racine carrée de la valeur pour une meilleure visualisation
            })),
            backgroundColor: "rgba(255, 99, 132, 0.5)",
        },
    ],
}));

const expenseChartData = computed(() => ({
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
            label: "Dépenses",
            data: props.expenseStats.data,
            borderColor: "rgba(54, 162, 235, 1)",
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            tension: 0.1,
            fill: true,
        },
    ],
}));

const expenseDistributionChartData = computed(() => ({
    labels: props.expenseDistributionStats.labels,
    datasets: [
        {
            label: "Répartition des Dépenses",
            data: props.expenseDistributionStats.data,
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "rgb(54, 162, 235)",
            pointBackgroundColor: "rgb(54, 162, 235)",
        },
    ],
}));

const userDistributionChartData = computed(() => ({
    labels: [
        "Administrateur",
        "Utilisateur",
        "Bailleur",
        "Locataire",
        "Autres",
    ],
    datasets: [
        {
            data: [
                users.value.filter((u) => u.role === "admin_entreprise").length,
                users.value.filter((u) => u.role === "user_entreprise").length,
                users.value.filter((u) => u.role === "bailleur").length,
                users.value.filter((u) => u.role === "locataire").length,
                users.value.filter(
                    (u) =>
                        ![
                            "admin_entreprise",
                            "user_entreprise",
                            "bailleur",
                            "locataire",
                        ].includes(u.role)
                ).length,
            ],
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

const tabClass = (tab) => {
    return `px-3 py-2 font-medium text-sm rounded-md ${
        activeTab.value === tab
            ? "bg-blue-100 text-blue-700"
            : "text-gray-500 hover:text-gray-700"
    }`;
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(value);
};
</script>
