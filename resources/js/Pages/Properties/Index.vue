<template>
    <AppLayout title="Catalogue Immobilier">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- En-tête -->
                <header class="mb-10">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <h1 class="text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0 flex items-center space-x-3">
                            <i class="fas fa-building text-indigo-600"></i>
                            <span>Catalogue Immobilier</span>
                        </h1>
                        <div v-if="canCreate" class="flex space-x-4">
                            <Link :href="route('properties.create')" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Ajouter un bien
                            </Link>
                            <button @click="toggleViewMode" class="btn-secondary">
                                <i :class="viewMode === 'grid' ? 'fas fa-th-list' : 'fas fa-th'"></i>
                                <span class="ml-2">{{ viewMode === 'grid' ? 'Vue Liste' : 'Vue Grille' }}</span>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Filtres -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Affiner la recherche</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="filter-date" class="block text-sm font-medium text-gray-700">Date
                                d'ajout</label>
                            <input type="date" id="filter-date" v-model="filters.date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div>
                            <label for="filter-owner" class="block text-sm font-medium text-gray-700">Bailleur</label>
                            <select id="filter-owner" v-model="filters.owner"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Tous les bailleurs</option>
                                <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
                                    {{ landlord.first_name }} {{ landlord.last_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-type" class="block text-sm font-medium text-gray-700">Type de
                                bien</label>
                            <select id="filter-type" v-model="filters.type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Tous les types</option>
                                <option value="Appartement">Appartement</option>
                                <option value="Maison">Maison</option>
                                <option value="Villa">Villa</option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-price" class="block text-sm font-medium text-gray-700">Prix max</label>
                            <input type="number" id="filter-price" v-model="filters.maxPrice"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Prix maximum" />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="applyFilters" class="btn-primary">
                            Appliquer les filtres
                        </button>
                        <button @click="resetFilters" class="btn-secondary">
                            Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Liste des propriétés -->
                <div v-if="!filteredProperties.length"
                    class="flex items-center justify-center h-64 bg-white rounded-lg shadow-md">
                    <div class="text-center">
                        <i class="fas fa-home text-6xl mb-4 text-indigo-300"></i>
                        <p class="text-xl text-gray-700">Aucun bien immobilier trouvé</p>
                    </div>
                </div>

                <div v-else>
                    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="property in paginatedProperties" :key="property.id"
                            class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative">
                                <img :src="getPropertyImage(property)" :alt="property.name"
                                    class="w-full h-48 object-cover" />
                                <div
                                    class="absolute top-0 left-0 bg-indigo-600 text-white text-xs uppercase px-3 py-1 rounded-br-lg">
                                    {{ property.property_type }}
                                </div>
                                <div
                                    class="absolute bottom-0 right-0 bg-green-500 text-white text-xs px-3 py-1 rounded-tl-lg">
                                    {{ property.available_count > 0 ? 'Disponible' : 'Indisponible' }}
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ property.name }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ property.description }}</p>
                                <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                    <span><i class="fas fa-map-marker-alt mr-1"></i> {{ property.address }}</span>
                                    <!--
                                    <span><i class="fas fa-ruler-combined mr-1"></i> {{ property.surface }} m²</span>
                                    -->
                                </div>
                                <div class="text-2xl font-bold text-indigo-600 mb-4">
                                    {{ formatPrice(property.price) }} CFA
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <button v-if="canUpdate(property)" @click="editProperty(property)"
                                            class="btn-icon">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="viewPropertyDetails(property)" class="btn-icon">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button v-if="canUpdate(property)" @click="togglePropertyStatus(property)"
                                            class="btn-icon">
                                            <i
                                                :class="['fas', property.available_count > 0 ? 'fa-toggle-on' : 'fa-toggle-off']"></i>
                                        </button>
                                        <button v-if="canDelete(property)" @click="openDeleteModal(property)"
                                            class="btn-icon text-red-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom du bien
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Adresse
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prix
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="property in paginatedProperties" :key="property.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ property.name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ property.property_type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ property.address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-indigo-600">{{ formatPrice(property.price)
                                            }} CFA</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            property.available_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                        ]">
                                            {{ property.available_count > 0 ? 'Disponible' : 'Indisponible' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button v-if="canUpdate(property)" @click="editProperty(property)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="viewPropertyDetails(property)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button v-if="canUpdate(property)" @click="togglePropertyStatus(property)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2">
                                            <i
                                                :class="['fas', property.available_count > 0 ? 'fa-toggle-on' : 'fa-toggle-off']"></i>
                                        </button>
                                        <button v-if="canDelete(property)" @click="openDeleteModal(property)"
                                            class="text-red-600 hover:text-red-900">
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
                            class="btn-secondary disabled:opacity-50 disabled:cursor-not-allowed">
                            Précédent
                        </button>
                        <span class="px-4 py-2 text-gray-700">{{ currentPage }} / {{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="btn-secondary disabled:opacity-50 disabled:cursor-not-allowed">
                            Suivant
                        </button>
                    </div>
                </div>

                <!-- Modal de suppression -->
                <Modal :show="showDeleteModal" @close="closeDeleteModal">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Êtes-vous sûr de vouloir supprimer le bien "{{ propertyToDelete?.name }}" ? Cette action est
                            irréversible.
                        </p>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button @click="closeDeleteModal" class="btn-secondary">Annuler</button>
                            <button @click="confirmDelete" class="btn-danger">Supprimer</button>
                        </div>
                    </div>
                </Modal>

                <!-- Modal de mise à jour du statut -->
                <Modal :show="showStatusModal" @close="closeStatusModal">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Mise à jour du statut</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Le statut du bien "{{ propertyStatusUpdate?.name }}" a été mis à jour avec succès.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <button @click="closeStatusModal" class="btn-primary">OK</button>
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
    maxPrice: null,
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
        const matchesPrice = !filters.value.maxPrice || property.price <= parseFloat(filters.value.maxPrice);
        return matchesDate && matchesOwner && matchesType && matchesPrice;
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
    filters.value = {
        date: '',
        owner: '',
        type: '',
        maxPrice: null,
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
    return '/img/default-property.jpg'; // Image par défaut
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR').format(price);
};

const editProperty = (property) => {
    router.get(route('properties.edit', property.id));
};

const viewPropertyDetails = (property) => {
    router.get(route('properties.show', property.id));
};
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
}

.btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150;
}

.btn-danger {
    @apply inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition ease-in-out duration-150;
}

.btn-icon {
    @apply p-2 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150;
}
</style>
