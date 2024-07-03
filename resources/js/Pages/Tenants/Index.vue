<template>
    <AppLayout title="Gestion des locataires">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                    <h1 class="text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0 flex items-center space-x-3">
                        <i class="fas fa-user text-indigo-600"></i>
                        <span>Gestion des locataires</span>
                    </h1>
                    <div v-if="canCreate" class="flex space-x-4">
                        <Link :href="route('tenants.create')"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-full shadow hover:bg-indigo-700 transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Ajouter un locataire
                        </Link>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Filtres</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="filter-date" class="block text-sm font-medium text-gray-700">Date d'ajout</label>
                            <input type="date" id="filter-date" v-model="filters.date"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                        <div>
                            <label for="filter-name" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" id="filter-name" v-model="filters.name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="applyFilters"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                            Appliquer
                        </button>
                        <button @click="resetFilters"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                            Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Tenants List -->
                <div v-if="!filteredTenants.length"
                    class="flex items-center justify-center h-64 bg-white rounded-lg shadow">
                    <div class="text-center">
                        <i class="fas fa-info-circle text-6xl mb-4 text-indigo-500"></i>
                        <p class="text-xl text-gray-700">Aucun locataire trouvé</p>
                    </div>
                </div>

                <div v-else>
                    <div class="bg-white rounded-lg shadow p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prénom
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Téléphone
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Adresse
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Numéro CNI/Passport
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date d'expiration
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="tenant in paginatedTenants" :key="tenant.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ tenant.first_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ tenant.last_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ tenant.email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ tenant.phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ tenant.address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ tenant.id_card_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ tenant.expiration_date }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link v-if="canUpdate(tenant)" :href="route('tenants.edit', tenant.id)"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <Link :href="route('tenants.show', tenant.id)"
                                            class="text-indigo-600 hover:text-indigo-900 ml-2">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <button v-if="canDelete(tenant)" @click="openDeleteModal(tenant)"
                                            class="text-red-600 hover:text-red-900 ml-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        <button @click="previousPage" :disabled="currentPage === 1"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
                            Précédent
                        </button>
                        <span class="px-4 py-2 text-gray-700">{{ currentPage }} / {{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
                            Suivant
                        </button>
                    </div>
                </div>

                <Modal :show="showDeleteModal" @close="closeDeleteModal">
                    <div class="p-6 bg-white rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Êtes-vous sûr de vouloir supprimer le locataire {{ tenantToDelete?.first_name }} {{ tenantToDelete?.last_name }} ? Cette action est irréversible.
                        </p>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button @click="closeDeleteModal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                                Annuler
                            </button>
                            <button @click="confirmDelete"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

const { props } = usePage();
const tenants = ref(props.tenants);
const user = props.auth.user;
const userRoles = computed(() => props.auth.roles || []);

const showDeleteModal = ref(false);
const tenantToDelete = ref(null);
const currentPage = ref(1);
const itemsPerPage = 10;

const filters = ref({
    date: '',
    name: '',
});

const canCreate = computed(() => {
    return Array.isArray(userRoles.value) && (userRoles.value.includes('super_admin') || userRoles.value.includes('admin_entreprise'));
});

const canUpdate = (tenant) => {
    return Array.isArray(userRoles.value) && (userRoles.value.includes('super_admin') || userRoles.value.includes('admin_entreprise'));
};

const canDelete = (tenant) => {
    return canUpdate(tenant);
};

const filteredTenants = computed(() => {
    return tenants.value.filter(tenant => {
        const matchesDate = !filters.value.date || new Date(tenant.created_at).toISOString().split('T')[0] === filters.value.date;
        const matchesName = !filters.value.name || (tenant.first_name.toLowerCase().includes(filters.value.name.toLowerCase()) || tenant.last_name.toLowerCase().includes(filters.value.name.toLowerCase()));
        return matchesDate && matchesName;
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
    filters.value.date = '';
    filters.value.name = '';
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
        router.delete(route('tenants.destroy', tenantToDelete.value.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                tenants.value = tenants.value.filter(t => t.id !== tenantToDelete.value.id);
            },
        });
    }
};
</script>

<style scoped>
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    border: 0;
}
</style>
