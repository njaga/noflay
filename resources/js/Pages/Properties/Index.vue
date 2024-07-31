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
                                <i :class="viewMode === 'grid'
                                        ? 'fas fa-th-list'
                                        : 'fas fa-th'
                                    "></i>
                                <span class="ml-2">{{
                                    viewMode === "grid"
                                        ? "Vue Liste"
                                        : "Vue Grille"
                                }}</span>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Filtres -->
                <Filters :landlords="landlords" :property-types="propertyTypes" :filters="filters"
                    @applyFilters="applyFilters" @resetFilters="resetFilters" />

                <!-- Liste des propriétés -->
                <div v-if="!filteredProperties.length"
                    class="flex items-center justify-center h-64 bg-white rounded-lg shadow-md">
                    <div class="text-center">
                        <i class="fas fa-home text-6xl mb-4 text-indigo-300"></i>
                        <p class="text-xl text-gray-700">
                            Aucun bien immobilier trouvé
                        </p>
                    </div>
                </div>

                <div v-else>
                    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <PropertyCard
      v-for="property in properties"
      :key="property.id"
      :property="property"
      :auth="auth"
      @openDeleteModal="openDeleteModal"
      @togglePropertyStatus="togglePropertyStatus"
    />
                    </div>

                    <PropertyTable v-else :properties="paginatedProperties" :auth="auth"
                        @openDeleteModal="openDeleteModal" @togglePropertyStatus="togglePropertyStatus" />

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
                <DeleteModal :show="showDeleteModal" :property="propertyToDelete" @close="closeDeleteModal"
                    @confirmDelete="confirmDelete" />

                <!-- Modal de mise à jour du statut -->
                <StatusModal :show="showStatusModal" :property="statusModalProperty" :message="statusModalMessage"
                    :type="statusType" @close="closeStatusModal" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Filters from "@/Components/Properties/Filters.vue";
import PropertyCard from "@/Components/Properties/PropertyCard.vue";
import PropertyTable from "@/Components/Properties/PropertyTable.vue";
import StatusModal from "@/Components/Properties/StatusModal.vue";
import DeleteModal from "@/Components/Properties/DeleteModal.vue";

const page = usePage();
const properties = ref(page.props.properties);
const landlords = ref(page.props.landlords);
const propertyTypes = ref(page.props.propertyTypes || []);
const auth = page.props.auth;
const userRoles = auth.roles;

const showDeleteModal = ref(false);
const propertyToDelete = ref(null);
const showStatusModal = ref(false);
const statusModalProperty = ref(null);
const statusModalMessage = ref("");
const statusType = ref("success"); // Initialisation de statusType
const viewMode = ref("grid");
const currentPage = ref(1);
const itemsPerPage = 9;

const filters = ref({
    date: "",
    owner: "",
    type: "",
    availability: "all",
});

const canCreate = computed(() => {
    return (
        userRoles.includes("super_admin") ||
        userRoles.includes("admin_entreprise")
    );
});

const filteredProperties = computed(() => {
    return properties.value.filter((property) => {
        const matchesDate =
            !filters.value.date ||
            new Date(property.created_at).toISOString().split("T")[0] ===
            filters.value.date;
        const matchesOwner =
            !filters.value.owner ||
            property.landlord_id === parseInt(filters.value.owner);
        const matchesType =
            !filters.value.type ||
            property.property_type === filters.value.type;
        const matchesAvailability =
            filters.value.availability === "all" ||
            (filters.value.availability === "available" &&
                property.available_count > 0) ||
            (filters.value.availability === "unavailable" &&
                property.available_count === 0);
        return (
            matchesDate && matchesOwner && matchesType && matchesAvailability
        );
    });
});

const totalPages = computed(() =>
    Math.ceil(filteredProperties.value.length / itemsPerPage)
);

const paginatedProperties = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredProperties.value.slice(start, end);
});

const handleFlashMessages = () => {
    const flash = page.props.flash;
    if (flash && flash.message) {
        showStatusModal.value = true;
        statusModalMessage.value = flash.message;
        statusModalProperty.value = null;
    }
};

onMounted(() => {
    handleFlashMessages();
});

watch(() => page.props.flash, handleFlashMessages);

const toggleViewMode = () => {
    viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};

const applyFilters = () => {
    currentPage.value = 1;
};

const resetFilters = () => {
    filters.value = {
        date: "",
        owner: "",
        type: "",
        availability: "all",
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
        const propertyId = propertyToDelete.value.id;
        router.delete(route("properties.destroy", propertyId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                properties.value = properties.value.filter(
                    (p) => p.id !== propertyId
                );
                showStatusModal.value = true;
                statusModalMessage.value = "Propriété supprimée avec succès.";
                statusType.value = "success"; // Définir le type sur success
                statusModalProperty.value = null;
            },
            onError: (errors) => {
                showStatusModal.value = true;
                statusType.value = "error"; // Définir le type sur error
                statusModalProperty.value = null;
                statusModalMessage.value =
                    "Une erreur est survenue lors de la suppression.";
                console.error(errors);
            },
        });
    }
};

const togglePropertyStatus = (property) => {
    const newAvailableCount = property.available_count > 0 ? 0 : 1;
    router.put(
        route("properties.update", property.id),
        { ...property, available_count: newAvailableCount },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                const updatedProperty = properties.value.find(
                    (p) => p.id === property.id
                );
                if (updatedProperty) {
                    updatedProperty.available_count = newAvailableCount;
                }
                statusModalProperty.value = property;
                statusModalMessage.value = `Le statut du bien "${property.name}" a été mis à jour avec succès.`;
                statusType.value = "success"; // Définir le type sur success
                showStatusModal.value = true;
            },
            onError: (errors) => {
                statusModalProperty.value = property;
                statusModalMessage.value =
                    "Une erreur est survenue lors de la mise à jour du statut.";
                statusType.value = "error"; // Définir le type sur error
                showStatusModal.value = true;
                console.error(errors);
            },
        }
    );
};

const closeStatusModal = () => {
    showStatusModal.value = false;
    statusModalProperty.value = null;
    statusModalMessage.value = "";
    statusType.value = "success"; // Réinitialiser le type sur success
};
</script>
<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
}

.btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150;
}
</style>
