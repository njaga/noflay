<template>
    <AppLayout title="Bailleurs">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header with title and add button -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0">
                        <i class="fas fa-users mr-2 text-indigo-600"></i>Liste des Bailleurs
                    </h1>
                    <div v-if="canManageLandlords">
                        <Link :href="route('landlords.create')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter un bailleur
                        </Link>

                        <!-- Bouton téléchargement -->
                        <button @click="handleDownloadPDF"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg ml-4">
                            <i class="fas fa-download mr-2"></i>Télécharger PDF
                        </button>
                    </div>
                </div>

                <!-- Search, filter, and view options -->
                <div class="mb-6 bg-white p-4 rounded-lg shadow-md">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full md:w-1/3 mb-4 md:mb-0">
                            <div class="relative">
                                <input type="text" placeholder="Rechercher un bailleur..."
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:border-indigo-500"
                                    v-model="search">
                                <div class="absolute left-3 top-2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-auto flex space-x-2">
                            <select v-model="sortBy"
                                class="rounded-lg border focus:outline-none focus:border-indigo-500 px-6 py-2">
                                <option value="first_name">Trier par prénom</option>
                                <option value="last_name">Trier par nom</option>
                                <option value="email">Trier par email</option>
                                <option value="company">Trier par entreprise</option>
                            </select>
                            <button @click="toggleSortOrder"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-150 ease-in-out">
                                <i
                                    :class="['fas', sortOrder === 'asc' ? 'fa-sort-alpha-down' : 'fa-sort-alpha-up']"></i>
                            </button>
                            <button @click="toggleView"
                                class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition duration-150 ease-in-out">
                                <i :class="['fas', viewMode === 'grid' ? 'fa-list' : 'fa-th-large']"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Landlords list/grid -->
                <div v-if="!paginatedLandlords.length"
                    class="text-center text-gray-500 py-10 bg-white rounded-lg shadow-md">
                    <i class="fas fa-info-circle text-4xl mb-4 text-indigo-500"></i>
                    <p>Aucun bailleur trouvé</p>
                </div>

                <div v-else :class="{ 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6': viewMode === 'grid' }">
                    <template v-if="viewMode === 'grid'">
                        <Card v-for="landlord in paginatedLandlords" :key="landlord.id"
                            :title="landlord.first_name + ' ' + landlord.last_name" :email="landlord.email"
                            :editUrl="route('landlords.edit', landlord.id)"
                            :viewUrl="route('landlords.show', landlord.id)"
                            :canManage="canManageLandlords"
                            @delete="openDeleteModal(landlord)"
                            @toggleStatus="toggleLandlordStatus(landlord)"
                            @createAccount="openCreateAccountModal(landlord)">
                            <template #extra-info>
                                <p class="text-sm text-gray-600">{{ landlord.company.name }}</p>
                            </template>
                        </Card>
                    </template>
                    <div v-else class="bg-white shadow-xl rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Téléphone
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pourcentage
                                    </th>
                                    <th v-if="canManageLandlords"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="landlord in paginatedLandlords" :key="landlord.id"
                                    class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ landlord.first_name }} {{ landlord.last_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ landlord.email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ landlord.phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ landlord.agency_percentage }} %</div>
                                    </td>
                                    <td v-if="canManageLandlords"
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('landlords.show', landlord.id)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('landlords.edit', landlord.id)"
                                            class="text-blue-600 hover:text-blue-900 mr-2">
                                        <i class="fas fa-edit"></i>
                                        </Link>
                                        <button @click="openDeleteModal(landlord)"
                                            class="text-red-600 hover:text-red-900 mr-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button @click="openCreateAccountModal(landlord)"
                                            class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="totalPages > 1" class="mt-6">
                    <div class="flex justify-between items-center">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50">
                            Précédent
                        </button>
                        <span>Page {{ currentPage }} sur {{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50">
                            Suivant
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete confirmation modal -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Êtes-vous sûr de vouloir supprimer le bailleur {{ landlordToDelete?.first_name }}
                    {{ landlordToDelete?.last_name }} ? Cette action est irréversible.
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

        <!-- Status update modal -->
        <Modal :show="showStatusModal" @close="closeStatusModal">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <i
                        :class="['fas', 'text-5xl', 'animate-pulse', landlordStatusUpdated?.is_active ? 'fa-check-circle text-indigo-500' : 'fa-times-circle text-red-500']"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mise à jour du statut</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Le bailleur {{ landlordStatusUpdated?.first_name }} {{ landlordStatusUpdated?.last_name }} a été
                    {{ landlordStatusUpdated?.is_active ? 'activé' : 'désactivé' }} avec succès.
                </p>
                <div class="mt-6 flex justify-center">
                    <button @click="closeStatusModal"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                        OK
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Create account modal -->
        <CreateAccountModal :show="showCreateAccountModal" :landlord="selectedLandlord" @close="closeCreateAccountModal" />
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Modal from '@/Components/Modal.vue';
import CreateAccountModal from '@/Components/Landlords/CreateAccountModal.vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

const props = defineProps({
    landlords: Array,
    auth: Object,
});

const landlords = ref(props.landlords);
const search = ref('');
const sortBy = ref('first_name');
const sortOrder = ref('asc');
const viewMode = ref('list');
const showDeleteModal = ref(false);
const landlordToDelete = ref(null);
const showStatusModal = ref(false);
const landlordStatusUpdated = ref(null);
const currentPage = ref(1);
const itemsPerPage = 10;
const showCreateAccountModal = ref(false);
const selectedLandlord = ref(null);

const filteredLandlords = computed(() => {
    let filtered = landlords.value.filter(landlord =>
        (landlord.first_name.toLowerCase().includes(search.value.toLowerCase()) ||
            landlord.last_name.toLowerCase().includes(search.value.toLowerCase()) ||
            landlord.email.toLowerCase().includes(search.value.toLowerCase()) ||
            landlord.company.name.toLowerCase().includes(search.value.toLowerCase()))
    );

    filtered.sort((a, b) => {
        let modifier = sortOrder.value === 'asc' ? 1 : -1;
        if (sortBy.value === 'first_name') {
            return modifier * a.first_name.localeCompare(b.first_name);
        } else if (sortBy.value === 'last_name') {
            return modifier * a.last_name.localeCompare(b.last_name);
        } else if (sortBy.value === 'email') {
            return modifier * a.email.localeCompare(b.email);
        } else {
            return modifier * a.company.name.localeCompare(b.company.name);
        }
    });

    return filtered;
});

const totalPages = computed(() => Math.ceil(filteredLandlords.value.length / itemsPerPage));

const paginatedLandlords = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredLandlords.value.slice(start, end);
});

const canManageLandlords = computed(() => {
    return props.auth?.user?.roles?.some(role => ['super_admin', 'admin_entreprise'].includes(role.name)) ?? false;
});

const toggleView = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};

const toggleSortOrder = () => {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const openDeleteModal = (landlord) => {
    landlordToDelete.value = landlord;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    landlordToDelete.value = null;
};

const confirmDelete = () => {
    router.delete(route('landlords.destroy', landlordToDelete.value.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            landlords.value = landlords.value.filter(l => l.id !== landlordToDelete.value.id);
        },
    });
};

const toggleLandlordStatus = async (landlord) => {
    try {
        const response = await axios.patch(route('landlords.toggle-status', landlord.id));
        const updatedLandlord = response.data;
        const index = landlords.value.findIndex(l => l.id === updatedLandlord.id);
        if (index !== -1) {
            landlords.value[index] = updatedLandlord;
        }
        landlordStatusUpdated.value = updatedLandlord;
        showStatusModal.value = true;
    } catch (error) {
        console.error('Error toggling landlord status:', error);
    }
};

const closeStatusModal = () => {
    showStatusModal.value = false;
    landlordStatusUpdated.value = null;
};

const handleDownloadPDF = () => {
    const doc = new jsPDF();
    doc.autoTable({
        head: [['Nom', 'Email', 'Entreprise']],
        body: filteredLandlords.value.map(landlord => [
            `${landlord.first_name} ${landlord.last_name}`,
            landlord.email,
            landlord.company.name
        ]),
    });
    doc.save('liste_bailleurs.pdf');
};

const openCreateAccountModal = (landlord) => {
    selectedLandlord.value = landlord;
    showCreateAccountModal.value = true;
};

const closeCreateAccountModal = () => {
    showCreateAccountModal.value = false;
};
</script>
