<template>
    <AppLayout title="Propriétés Archivée">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div
                    class="flex flex-col space-y-4 sm:flex-row sm:justify-between sm:items-center mb-8"
                >
                    <h2
                        class="text-2xl sm:text-3xl font-extrabold text-indigo-900"
                    >
                        Propriétés Archivée
                    </h2>
                    <div class="flex space-x-4">
                        <button @click="toggleViewMode" class="btn-secondary">
                            <i
                                :class="
                                    viewMode === 'grid'
                                        ? 'fas fa-th-list'
                                        : 'fas fa-th'
                                "
                            ></i>
                            <span class="ml-2">{{
                                viewMode === "grid" ? "Vue Liste" : "Vue Grille"
                            }}</span>
                        </button>
                    </div>
                </div>

                <div
                    v-if="paginatedProperties.length === 0"
                    class="flex items-center justify-center h-64 bg-white rounded-lg shadow-md"
                >
                    <div class="text-center">
                        <i
                            class="bi bi-archive text-6xl mb-4 text-indigo-300"
                        ></i>
                        <p class="text-xl text-gray-700">
                            Aucune propriété archivée
                        </p>
                    </div>
                </div>

                <div v-else>
                    <div
                        v-if="viewMode === 'grid'"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="property in paginatedProperties"
                            :key="property.id"
                            class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                        >
                            <div class="relative">
                                <img
                                    :src="getPropertyImage(property)"
                                    :alt="property.name"
                                    class="w-full h-48 object-cover"
                                />
                                <div
                                    class="absolute top-0 left-0 bg-red-600 text-white text-xs uppercase px-3 py-1 rounded-br-lg"
                                >
                                    Archivée
                                </div>
                            </div>
                            <div class="p-5">
                                <h3
                                    class="text-2xl font-semibold text-gray-900 mb-2"
                                >
                                    {{ property.name }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    {{ property.description }}
                                </p>
                                <div
                                    class="flex justify-between items-center text-sm text-gray-500 mb-4"
                                >
                                    <span
                                        ><i class="bi bi-geo-alt mr-1"></i>
                                        {{ property.address }}</span
                                    >
                                </div>
                                <div
                                    class="text-2xl font-bold text-indigo-600 mb-4"
                                >
                                    {{ formatCurrency(property.price) }}
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="restoreProperty(property)"
                                            class="btn-icon"
                                        >
                                            <i
                                                class="bi bi-arrow-counterclockwise"
                                            ></i>
                                        </button>
                                        <Link
                                            :href="
                                                route(
                                                    'properties.showArchived',
                                                    property.id
                                                )
                                            "
                                            class="btn-icon"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </Link>
                                        <button
                                            @click="openDeleteModal(property)"
                                            class="btn-icon text-red-600"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="bg-white rounded-lg shadow-md overflow-hidden"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Nom du bien
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Type
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Adresse
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Prix
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Statut
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="property in paginatedProperties"
                                    :key="property.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ property.name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ property.property_type }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ property.address }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-indigo-600"
                                        >
                                            {{ formatCurrency(property.price) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                property.available_count > 0
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            ]"
                                        >
                                            {{
                                                property.available_count > 0
                                                    ? "Disponible"
                                                    : "Indisponible"
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                    >
                                        <button
                                            @click="restoreProperty(property)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2"
                                        >
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                        <Link
                                            :href="
                                                route(
                                                    'properties.showArchived',
                                                    property.id
                                                )
                                            "
                                            class="text-indigo-600 hover:text-indigo-900 mr-2"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <button
                                            @click="openDeleteModal(property)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        <button
                            @click="previousPage"
                            :disabled="currentPage === 1"
                            class="btn-secondary disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Précédent
                        </button>
                        <span class="px-4 py-2 text-gray-700"
                            >{{ currentPage }} / {{ totalPages }}</span
                        >
                        <button
                            @click="nextPage"
                            :disabled="currentPage === totalPages"
                            class="btn-secondary disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Suivant
                        </button>
                    </div>
                </div>

                <!-- Modal de confirmation de suppression -->
                <ModalArchive
  :open="showDeleteModal"
  title="Supprimer Définitivement la Propriété"
  :message="`Êtes-vous sûr de vouloir supprimer définitivement la propriété ${propertyToDelete?.name} ? Cette action est irréversible.`"
  status="error"
  @close="closeDeleteModal"
  @confirm="confirmDelete"
/>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ModalArchive from "@/Components/Properties/ModalArchive.vue";

const { props } = usePage();
const archivedProperties = ref(props.archivedProperties || []);
const showDeleteModal = ref(false);
const propertyToDelete = ref(null);
const viewMode = ref("grid");
const currentPage = ref(1);
const itemsPerPage = 10;

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "CFA",
    }).format(amount);
};

const getPropertyImage = (property) => {
    if (property.photos && property.photos.length) {
        const parsedPhotos = JSON.parse(property.photos);
        if (parsedPhotos.length > 0) {
            const photoPath = parsedPhotos[0].replace(/^public\//, "");
            return `/storage/${photoPath}`;
        }
    }
    return "/img/default-property.jpg";
};

const toggleViewMode = () => {
    viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};

const filteredProperties = computed(() => archivedProperties.value);

const totalPages = computed(() =>
    Math.ceil(filteredProperties.value.length / itemsPerPage)
);

const paginatedProperties = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredProperties.value.slice(start, end);
});

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

const restoreProperty = (property) => {
    router.post(
        route("properties.restore", property.id),
        {},
        {
            onSuccess: () => {
                archivedProperties.value = archivedProperties.value.filter(
                    (p) => p.id !== property.id
                );
            },
        }
    );
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
        router.post(
            route("properties.forceDelete", propertyId),
            {},
            {
                onSuccess: () => {
                    closeDeleteModal();
                    archivedProperties.value = archivedProperties.value.filter(
                        (p) => p.id !== propertyId
                    );
                },
                onError: (errors) => {
                    console.error(errors);
                },
            }
        );
    }
};
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
}

.btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150;
}

.btn-icon {
    @apply p-2 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150;
}
</style>
