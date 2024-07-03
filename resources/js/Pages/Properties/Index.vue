<template>
    <AppLayout title="Liste des propriétés">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                    <h1 class="text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0 flex items-center space-x-3">
                        <i class="fas fa-building text-indigo-600"></i>
                        <span>Liste des propriétés</span>
                    </h1>
                    <div v-if="canCreate" class="flex space-x-4">
                        <Link :href="route('properties.create')"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-full shadow hover:bg-indigo-700 transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Ajouter une propriété
                        </Link>
                        <button @click="toggleViewMode"
                            class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-full shadow hover:bg-purple-700 transition duration-300">
                            <i :class="viewMode === 'grid' ? 'fas fa-th-list' : 'fas fa-th'"></i>
                            <span class="ml-2">{{ viewMode === 'grid' ? 'Vue Liste' : 'Vue Grille' }}</span>
                        </button>
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
                            <label for="filter-owner" class="block text-sm font-medium text-gray-700">Bailleur</label>
                            <select id="filter-owner" v-model="filters.owner"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Sélectionnez un bailleur</option>
                                <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
                                    {{ landlord.first_name }} {{ landlord.last_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-type" class="block text-sm font-medium text-gray-700">Type de propriété</label>
                            <select id="filter-type" v-model="filters.type"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Tous</option>
                                <option value="Appartement">Appartement</option>
                                <option value="Maison">Maison</option>
                                <option value="Villa">Villa</option>
                            </select>
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

                <!-- Properties List -->
                <div v-if="!filteredProperties.length"
                    class="flex items-center justify-center h-64 bg-white rounded-lg shadow">
                    <div class="text-center">
                        <i class="fas fa-info-circle text-6xl mb-4 text-indigo-500"></i>
                        <p class="text-xl text-gray-700">Aucune propriété trouvée</p>
                    </div>
                </div>

                <div v-else>
                    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="property in paginatedProperties" :key="property.id"
                            class="bg-white rounded-lg overflow-hidden shadow hover:shadow-xl transition duration-300">
                            <div class="relative">
                                <img :src="getPropertyImage(property)" alt="Property Image" class="w-full h-48 object-cover" />
                                <div class="absolute top-0 left-0 bg-indigo-600 text-white text-xs uppercase px-3 py-1 rounded-br-lg">
                                    {{ property.property_type }}
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-2xl font-semibold text-gray-900">{{ property.name }}</h3>
                                <p class="mt-3 text-sm text-gray-700">{{ property.description }}</p>
                                <div class="mt-6 flex justify-between items-center">
                                    <div class="text-sm text-gray-500">{{ property.available_count }} disponibles</div>
                                    <div class="flex space-x-4">
                                        <Link v-if="canUpdate(property)" :href="route('properties.edit', property.id)"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <Link :href="route('properties.show', property.id)"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <button v-if="canUpdate(property)" @click="togglePropertyStatus(property)"
                                            class="text-red-600 hover:text-red-900">
                                            <i :class="['fas', property.available_count > 0 ? 'fa-toggle-on' : 'fa-toggle-off']"></i>
                                        </button>
                                        <button v-if="canDelete(property)" @click="openDeleteModal(property)"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="bg-white rounded-lg shadow p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom de la propriété
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Disponibles
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="property in paginatedProperties" :key="property.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ property.name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ property.property_type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ property.description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ property.available_count }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link v-if="canUpdate(property)" :href="route('properties.edit', property.id)"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <Link :href="route('properties.show', property.id)"
                                            class="text-indigo-600 hover:text-indigo-900 ml-2">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <button v-if="canUpdate(property)" @click="togglePropertyStatus(property)"
                                            class="text-red-600 hover:text-red-900 ml-2">
                                            <i :class="['fas', property.available_count > 0 ? 'fa-toggle-on' : 'fa-toggle-off']"></i>
                                        </button>
                                        <button v-if="canDelete(property)" @click="openDeleteModal(property)"
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
                            Êtes-vous sûr de vouloir supprimer la propriété {{ propertyToDelete?.name }} ? Cette action est irréversible.
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

                <Modal :show="showStatusModal" @close="closeStatusModal">
                    <div class="p-6 bg-white rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Mise à jour du statut</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Le statut de la propriété {{ propertyStatusUpdate?.name }} a été mis à jour avec succès.
                        </p>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button @click="closeStatusModal"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                                OK
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
const properties = ref(props.properties);
const landlords = ref(props.landlords);
const user = props.auth.user;
const userRoles = props.auth.roles;

const showDeleteModal = ref(false);
const propertyToDelete = ref(null);
const showStatusModal = ref(false);
const propertyStatusUpdate = ref(null);
const viewMode = ref('grid');
const currentPage = ref(1);
const itemsPerPage = 9;

const filters = ref({
    date: '',
    owner: '',
    type: '',
});

const canCreate = computed(() => {
    return userRoles.includes('super_admin') || userRoles.includes('admin_entreprise');
});

const canUpdate = (property) => {
    return (
        userRoles.includes('super_admin') ||
        (userRoles.includes('admin_entreprise') && user.company_id === property.company_id)
    );
};

const canDelete = (property) => {
    return canUpdate(property);
};

const filteredProperties = computed(() => {
    return properties.value.filter(property => {
        const matchesDate = !filters.value.date || new Date(property.created_at).toISOString().split('T')[0] === filters.value.date;
        const matchesOwner = !filters.value.owner || property.landlord_id === parseInt(filters.value.owner);
        const matchesType = !filters.value.type || property.property_type === filters.value.type;
        return matchesDate && matchesOwner && matchesType;
    });
});

const totalPages = computed(() => Math.ceil(filteredProperties.value.length / itemsPerPage));

const paginatedProperties = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredProperties.value.slice(start, end);
});

const toggleViewMode = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};

const applyFilters = () => {
    currentPage.value = 1;
};

const resetFilters = () => {
    filters.value.date = '';
    filters.value.owner = '';
    filters.value.type = '';
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

const openDeleteModal = (property) => {
    propertyToDelete.value = property;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    propertyToDelete.value = null;
};

const confirmDelete = () => {
    if (propertyToDelete.value) {
        router.delete(route('properties.destroy', propertyToDelete.value.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                properties.value = properties.value.filter(p => p.id !== propertyToDelete.value.id);
            },
        });
    }
};

const togglePropertyStatus = (property) => {
    property.available_count = property.available_count > 0 ? 0 : 1;
    router.put(route('properties.update', property.id), property, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            propertyStatusUpdate.value = property;
            showStatusModal.value = true;
        },
    });
};

const closeStatusModal = () => {
    showStatusModal.value = false;
    propertyStatusUpdate.value = null;
};

const getPropertyImage = (property) => {
    if (property.photos && property.photos.length) {
        const parsedPhotos = JSON.parse(property.photos);
        if (parsedPhotos.length > 0) {
            const photoPath = parsedPhotos[0].replace(/^public\//, '');
            return `/storage/${photoPath}`;
        }
    }
    return 'https://via.placeholder.com/150'; // Fallback image
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
