<template>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <!-- Version desktop -->
            <table class="w-full divide-y divide-gray-200 hidden lg:table">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600">
                    <tr>
                        <th
                            v-for="header in tableHeaders"
                            :key="header"
                            scope="col"
                            class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"
                        >
                            {{ header }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="paginatedTenants.length === 0">
                        <td
                            colspan="7"
                            class="px-4 py-8 whitespace-nowrap text-center"
                        >
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <i
                                    class="fas fa-user-slash text-4xl text-gray-400 mb-3"
                                ></i>
                                <span class="text-xl font-medium text-gray-500"
                                    >Aucun locataire trouvé</span
                                >
                            </div>
                        </td>
                    </tr>
                    <tr
                        v-else
                        v-for="tenant in paginatedTenants"
                        :key="tenant.id"
                        class="hover:bg-gray-50 transition duration-150 ease-in-out"
                    >
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center"
                                >
                                    <span
                                        class="text-sm font-medium text-white"
                                    >
                                        {{
                                            getInitials(
                                                tenant.first_name,
                                                tenant.last_name
                                            )
                                        }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ tenant.first_name }}
                                        {{ tenant.last_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ tenant.email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ tenant.contracts[0]?.property.name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ tenant.contracts[0]?.property.address }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span
                                :class="[
                                    'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    isActiveTenant(tenant)
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800',
                                ]"
                            >
                                {{
                                    isActiveTenant(tenant) ? "Actif" : "Inactif"
                                }}
                            </span>
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(tenant.contracts[0]?.start_date) }}
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(tenant.contracts[0]?.end_date) }}
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium"
                        >
                            <div class="flex justify-end space-x-2">
                                <Link
                                    v-if="canUpdateTenant"
                                    :href="route('tenants.edit', tenant.id)"
                                    class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out"
                                >
                                    <i class="fas fa-edit"></i>
                                </Link>
                                <Link
                                    :href="route('tenants.show', tenant.id)"
                                    class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out"
                                >
                                    <i class="fas fa-eye"></i>
                                </Link>
                                <button
                                    v-if="canDeleteTenant"
                                    @click="openDeleteModal(tenant)"
                                    class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button
                                    @click="$emit('createAccount', tenant)"
                                    class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out"
                                >
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Version mobile -->
            <div class="lg:hidden">
                <div
                    v-if="paginatedTenants.length === 0"
                    class="p-4 text-center"
                >
                    <div class="flex flex-col items-center justify-center">
                        <i
                            class="fas fa-user-slash text-4xl text-gray-400 mb-3"
                        ></i>
                        <span class="text-xl font-medium text-gray-500"
                            >Aucun locataire trouvé</span
                        >
                    </div>
                </div>
                <div
                    v-else
                    v-for="tenant in paginatedTenants"
                    :key="tenant.id"
                    class="p-4 border-b border-gray-200 last:border-b-0"
                >
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center"
                            >
                                <span class="text-sm font-medium text-white">
                                    {{
                                        getInitials(
                                            tenant.first_name,
                                            tenant.last_name
                                        )
                                    }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ tenant.first_name }}
                                    {{ tenant.last_name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ tenant.email }}
                                </div>
                            </div>
                        </div>
                        <span
                            :class="[
                                'px-2 py-1 text-xs leading-5 font-semibold rounded-full',
                                isActiveTenant(tenant)
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800',
                            ]"
                        >
                            {{ isActiveTenant(tenant) ? "Actif" : "Inactif" }}
                        </span>
                    </div>
                    <div class="mt-2 space-y-1">
                        <div class="text-sm">
                            <span class="font-medium text-gray-500"
                                >Propriété:</span
                            >
                            <span class="ml-1 text-gray-900">{{
                                tenant.contracts[0]?.property.name
                            }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-medium text-gray-500"
                                >Adresse:</span
                            >
                            <span class="ml-1 text-gray-900">{{
                                tenant.contracts[0]?.property.address
                            }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-medium text-gray-500"
                                >Date d'entrée:</span
                            >
                            <span class="ml-1 text-gray-900">{{
                                formatDate(tenant.contracts[0]?.start_date)
                            }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-medium text-gray-500"
                                >Fin de contrat:</span
                            >
                            <span class="ml-1 text-gray-900">{{
                                formatDate(tenant.contracts[0]?.end_date)
                            }}</span>
                        </div>
                    </div>
                    <div class="mt-3 flex justify-end space-x-2">
                        <Link
                            v-if="canUpdateTenant"
                            :href="route('tenants.edit', tenant.id)"
                            class="text-indigo-600 hover:text-indigo-900"
                        >
                            <i class="fas fa-edit"></i>
                        </Link>
                        <Link
                            :href="route('tenants.show', tenant.id)"
                            class="text-blue-600 hover:text-blue-900"
                        >
                            <i class="fas fa-eye"></i>
                        </Link>
                        <button
                            v-if="canDeleteTenant"
                            @click="openDeleteModal(tenant)"
                            class="text-red-600 hover:text-red-900"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                        <button
                            @click="$emit('createAccount', tenant)"
                            class="text-green-600 hover:text-green-900"
                        >
                            <i class="fas fa-user-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    paginatedTenants: Array,
    canUpdateTenant: Boolean,
    canDeleteTenant: Boolean,
});

const emit =  defineEmits(["openDeleteModal", "createAccount"]);

const tableHeaders = [
    "Locataire",
    "Propriété",
    "Statut",
    "Date d'entrée",
    "Fin de contrat",
    "Actions",
];

const openDeleteModal = (tenant) => {
    emit("openDeleteModal", tenant);
};

const formatDate = (dateString) => {
    if (!dateString || dateString === "N/A") return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    const date = new Date(dateString);
    return isNaN(date)
        ? "Invalid Date"
        : date.toLocaleDateString("fr-FR", options);
};

const getInitials = (firstName, lastName) => {
    return `${firstName.charAt(0)}${lastName.charAt(0)}`.toUpperCase();
};

const isActiveTenant = (tenant) => {
    return tenant.contracts.some((c) => new Date(c.end_date) >= new Date());
};
</script>

<style scoped>
.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}
</style>
