<template>
    <AppLayout title="Liste des contrats">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold leading-tight text-gray-800">Liste des contrats</h2>
                        <table class="min-w-full divide-y divide-gray-200 mt-6">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de contrat</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Locataire</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriété</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de début</th>
                                    <th class="px-6 py-3 bg-gray-50"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="contract in contracts" :key="contract.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ contract.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ contract.contract_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ contract.tenant.first_name }} {{ contract.tenant.last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ contract.property.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ contract.start_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('contracts.edit', contract.id)" class="text-indigo-600 hover:text-indigo-900">Modifier</Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const { props } = usePage();
const contracts = ref(props.contracts);
</script>
