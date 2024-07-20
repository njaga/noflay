<template>
    <AppLayout title="Tableau de bord - Locataire">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-3xl font-bold mb-8 text-gray-800">Tableau de bord - Locataire</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            <StatCard title="Contrats Actifs" :value="activeContracts.length" icon="file-text" color="bg-green-500" />
                            <StatCard title="Paiements en Retard" :value="overduePayments.length" icon="exclamation-circle" color="bg-red-500" />
                            <StatCard title="Propriétés" :value="properties.length" icon="home" color="bg-yellow-500" />
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-semibold mb-4">Mes Propriétés</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loyer</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="property in properties" :key="property.id">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ property.name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ property.address }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ formatCurrency(property.price) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
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

const { props } = usePage();
const activeContracts = ref(props.activeContracts || []);
const overduePayments = ref(props.overduePayments || []);
const properties = ref(props.properties || []);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
};
</script>

<style scoped>
/* Ajoutez ici des styles personnalisés si nécessaire */
</style>
