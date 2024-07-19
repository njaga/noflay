<template>
    <AppLayout title="Tableau de bord - Super Administrateur">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-3xl font-bold mb-8 text-gray-800">Tableau de bord - Super Administrateur</h2>
                        <!-- Onglets pour la navigation -->
                        <div class="mb-8">
                            <nav class="flex space-x-4" aria-label="Tabs">
                                <button @click="activeTab = 'overview'" :class="tabClass('overview')">Aperçu</button>
                                <button @click="activeTab = 'statistics'" :class="tabClass('statistics')">Statistiques</button>
                                <button @click="activeTab = 'companies'" :class="tabClass('companies')">Entreprises</button>
                            </nav>
                        </div>

                        <!-- Contenu des onglets -->
                        <div v-if="activeTab === 'overview'">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                                <StatCard title="Entreprises" :value="companies.length" icon="building" color="bg-blue-500" />
                                <StatCard title="Utilisateurs" :value="users.length" icon="users" color="bg-green-500" />
                                <StatCard title="Contrats" :value="contracts.length" icon="file-text" color="bg-yellow-500" />
                                <StatCard title="Propriétés" :value="properties.length" icon="home" color="bg-purple-500" />
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <ChartCard title="Évolution des Contrats">
                                    <LineChart :chart-data="contractChartData" />
                                </ChartCard>
                                <ChartCard title="Répartition des Propriétés">
                                    <PieChart :chart-data="propertyTypesChartData" />
                                </ChartCard>
                            </div>
                        </div>

                        <div v-if="activeTab === 'statistics'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <ChartCard title="Revenus Mensuels">
                                    <BarChart :chart-data="revenueChartData" />
                                </ChartCard>
                                <ChartCard title="Taux d'Occupation">
                                    <LineChart :chart-data="occupancyRateChartData" />
                                </ChartCard>
                            </div>
                        </div>

                        <div v-if="activeTab === 'companies'">
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <h3 class="text-xl font-semibold mb-4">Entreprises Récentes</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="company in recentCompanies" :key="company.id">
                                                <td class="px-6 py-4 whitespace-nowrap">{{ company.name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ company.address }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ company.email }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
import ChartCard from '@/Components/ChartCard.vue';
import LineChart from '@/Components/Charts/LineChart.vue';
import PieChart from '@/Components/Charts/PieChart.vue';
import BarChart from '@/Components/Charts/BarChart.vue';

const { props } = usePage();
const activeTab = ref('overview');

const companies = ref(props.companies || []);
const users = ref(props.users || []);
const contracts = ref(props.contracts || []);
const properties = ref(props.properties || []);
const recentCompanies = computed(() => companies.value.slice(0, 5));

const contractChartData = computed(() => ({
    labels: props.contractStats?.labels || [],
    datasets: [{
        label: 'Nombre de Contrats',
        data: props.contractStats?.data || [],
        borderColor: 'rgba(75, 192, 192, 1)',
        tension: 0.1
    }]
}));

const propertyTypesChartData = computed(() => ({
    labels: props.propertyTypes?.labels || [],
    datasets: [{
        data: props.propertyTypes?.data || [],
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
    }]
}));

const revenueChartData = computed(() => ({
    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
    datasets: [{
        label: 'Revenus',
        data: [12000, 19000, 15000, 21000, 16000, 23000],
        backgroundColor: 'rgba(54, 162, 235, 0.5)'
    }]
}));

const occupancyRateChartData = computed(() => ({
    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
    datasets: [{
        label: 'Taux d\'Occupation',
        data: [85, 88, 90, 92, 91, 93],
        borderColor: 'rgba(255, 99, 132, 1)',
        tension: 0.1
    }]
}));

const tabClass = (tab) => {
    return `px-3 py-2 font-medium text-sm rounded-md ${activeTab.value === tab
            ? 'bg-blue-100 text-blue-700'
            : 'text-gray-500 hover:text-gray-700'
        }`;
};
</script>

<style scoped>
/* Ajoutez ici des styles personnalisés si nécessaire */
</style>
