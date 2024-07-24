<template>
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Locataire</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriété</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'entrée</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fin de contrat</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="tenant in tenants" :key="tenant.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ getInitials(tenant.first_name, tenant.last_name) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ tenant.first_name }} {{ tenant.last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ tenant.email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ tenant.contracts[0]?.property.name }}</div>
                            <div class="text-sm text-gray-500">{{ tenant.contracts[0]?.property.address }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="[
                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                tenant.contracts[0]?.status === 'active' ? 'bg-green-100 text-green-800' : tenant.contracts[0]?.status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                            ]">
                                {{ tenant.contracts[0]?.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ formatDate(tenant.contracts[0]?.start_date) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ formatDate(tenant.contracts[0]?.end_date) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <Link v-if="canUpdateTenant" :href="route('tenants.edit', tenant.id)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                <i class="fas fa-edit"></i>
                            </Link>
                            <Link :href="route('tenants.show', tenant.id)" class="text-blue-600 hover:text-blue-900 mr-2">
                                <i class="fas fa-eye"></i>
                            </Link>
                            <button v-if="canDeleteTenant" @click="openDeleteModal(tenant)" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    tenants: Array,
    canUpdateTenant: Boolean,
    canDeleteTenant: Boolean,
});

const emit =  defineEmits(['open-delete-modal']);

const openDeleteModal = (tenant) => {
    emit('open-delete-modal', tenant);
};

const formatDate = (dateString) => {
    if (!dateString || dateString === 'N/A') return 'N/A';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return isNaN(date) ? 'Invalid Date' : date.toLocaleDateString('fr-FR', options);
};

const getInitials = (firstName, lastName) => {
    return `${firstName.charAt(0)}${lastName.charAt(0)}`.toUpperCase();
};
</script>
