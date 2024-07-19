<template>
    <AppLayout title="Gestion des locataires">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                    <h1 class="text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0 flex items-center space-x-3">
                        <i class="fas fa-building text-indigo-600"></i>
                        <span>Gestion des locataires</span>
                    </h1>
                    <div v-if="canCreateTenant" class="flex space-x-4">
                        <Link
                            :href="route('tenants.create')"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full shadow-lg hover:from-indigo-700 hover:to-indigo-600 transition duration-300 transform hover:scale-105"
                        >
                            <i class="fas fa-user-plus mr-2"></i>Nouveau locataire
                        </Link>
                    </div>
                </div>

                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-user-check text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Locataires actifs</p>
                            <p class="text-2xl font-semibold text-gray-800">{{ activeTenants }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-2xl text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Contrats expirant bientôt</p>
                            <p class="text-2xl font-semibold text-gray-800">{{ expiringContracts }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-home text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Propriétés louées</p>
                            <p class="text-2xl font-semibold text-gray-800">{{ rentedProperties }}</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Filtres avancés</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="filter-date" class="block text-sm font-medium text-gray-700">Date d'entrée</label>
                            <input
                                type="date"
                                id="filter-date"
                                v-model="filters.date"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <label for="filter-name" class="block text-sm font-medium text-gray-700">Nom du locataire</label>
                            <input
                                type="text"
                                id="filter-name"
                                v-model="filters.name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <label for="filter-property" class="block text-sm font-medium text-gray-700">Propriété</label>
                            <select
                                id="filter-property"
                                v-model="filters.property"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Toutes les propriétés</option>
                                <option v-for="property in properties" :key="property.id" :value="property.id">
                                    {{ property.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-status" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select
                                id="filter-status"
                                v-model="filters.status"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Tous les statuts</option>
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                                <option value="expiring">Contrat expirant</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="applyFilters" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                            <i class="fas fa-search mr-2"></i>Rechercher
                        </button>
                        <button @click="resetFilters" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                            <i class="fas fa-undo mr-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Tenants List -->
                <div v-if="!filteredTenants.length" class="flex items-center justify-center h-64 bg-white rounded-xl shadow-md">
                    <div class="text-center">
                        <i class="fas fa-home text-6xl mb-4 text-indigo-300"></i>
                        <p class="text-xl text-gray-700">Aucun locataire trouvé</p>
                    </div>
                </div>

                <div v-else>
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
                                    <tr v-for="tenant in paginatedTenants" :key="tenant.id" class="hover:bg-gray-50">
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
                                            <div class="text-sm text-gray-900">{{ tenant.property }}</div>
                                            <div class="text-sm text-gray-500">{{ tenant.property_address }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                tenant.status === 'active' ? 'bg-green-100 text-green-800' : tenant.status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                                            ]">
                                                {{ tenant.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(tenant.start_date) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(tenant.end_date) }}
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

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button @click="previousPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Précédent</span>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                {{ currentPage }} / {{ totalPages }}
                            </span>
                            <button @click="nextPage" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Suivant</span>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </nav>
                    </div>
                </div>

                <Modal :show="showDeleteModal" @close="closeDeleteModal">
                    <div class="p-6 bg-white rounded-lg shadow-xl">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Êtes-vous sûr de vouloir supprimer le locataire {{ tenantToDelete?.first_name }} {{ tenantToDelete?.last_name }} ? Cette action est irréversible.
                        </p>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button @click="closeDeleteModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">Annuler</button>
                            <button @click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">Supprimer</button>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Components/Modal.vue";

const { props } = usePage();
const tenants = ref(props.tenants);
const properties = ref(props.properties);
const user = props.auth.user;
const userRoles = computed(() => props.auth.roles || []);

const showDeleteModal = ref(false);
const tenantToDelete = ref(null);
const currentPage = ref(1);
const itemsPerPage = 10;

const filters = ref({
    date: "",
    name: "",
    property: "",
    status: "",
});

// Dashboard data
const activeTenants = ref(0);
const expiringContracts = ref(0);
const rentedProperties = ref(0);

onMounted(() => {
    // Calculate dashboard data
    activeTenants.value = tenants.value.filter((t) => t.status === "active").length;
    expiringContracts.value = tenants.value.filter((t) => {
        const endDate = new Date(t.end_date);
        const oneMonthFromNow = new Date();
        oneMonthFromNow.setMonth(oneMonthFromNow.getMonth() + 1);
        return endDate <= oneMonthFromNow && t.status === "active";
    }).length;
    rentedProperties.value = [...new Set(tenants.value.filter((t) => t.property).map((t) => t.property.id))].length;
});

const canCreateTenant = computed(() => {
    return userRoles.value.includes("super_admin") || userRoles.value.includes("admin_entreprise");
});

const canUpdateTenant = computed(() => {
    return userRoles.value.includes("super_admin") || userRoles.value.includes("admin_entreprise");
});

const canDeleteTenant = computed(() => {
    return userRoles.value.includes("super_admin") || userRoles.value.includes("admin_entreprise");
});

const filteredTenants = computed(() => {
    return tenants.value.filter((tenant) => {
        const matchesDate = !filters.value.date || new Date(tenant.start_date).toISOString().split("T")[0] === filters.value.date;
        const matchesName = !filters.value.name || (tenant.first_name && tenant.first_name.toLowerCase().includes(filters.value.name.toLowerCase())) || (tenant.last_name && tenant.last_name.toLowerCase().includes(filters.value.name.toLowerCase()));
        const matchesProperty = !filters.value.property || (tenant.property && tenant.property.id === filters.value.property);
        const matchesStatus = !filters.value.status || tenant.status === filters.value.status;
        return matchesDate && matchesName && matchesProperty && matchesStatus;
    });
});

const totalPages = computed(() => Math.ceil(filteredTenants.value.length / itemsPerPage));

const paginatedTenants = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredTenants.value.slice(start, end);
});

const applyFilters = () => {
    currentPage.value = 1;
};

const resetFilters = () => {
    filters.value = {
        date: "",
        name: "",
        property: "",
        status: "",
    };
    applyFilters();
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const openDeleteModal = (tenant) => {
    tenantToDelete.value = tenant;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    tenantToDelete.value = null;
};

const confirmDelete = () => {
    if (tenantToDelete.value) {
        router.delete(route("tenants.destroy", tenantToDelete.value.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                tenants.value = tenants.value.filter((t) => t.id !== tenantToDelete.value.id);
                activeTenants.value = tenants.value.filter((t) => t.status === "active").length;
                rentedProperties.value = [...new Set(tenants.value.filter((t) => t.property).map((t) => t.property.id))].length;
            },
        });
    }
};

const formatDate = (dateString) => {
    if (!dateString || dateString === "N/A") return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    const date = new Date(dateString);
    return isNaN(date) ? "Invalid Date" : date.toLocaleDateString("fr-FR", options);
};

const getInitials = (firstName, lastName) => {
    return `${firstName.charAt(0)}${lastName.charAt(0)}`.toUpperCase();
};
</script>

<style scoped>
.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

.from-blue-50 {
    --tw-gradient-from: #eff6ff;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(239, 246, 255, 0));
}

.to-indigo-100 {
    --tw-gradient-to: #e0e7ff;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}

.transition {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.duration-300 {
    transition-duration: 300ms;
}

.rounded-xl {
    border-radius: 0.75rem;
}

.shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>
