<template>
    <AppLayout title="Gestion des Contrats">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center mb-8"
                >
                    <h1
                        class="text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0 flex items-center space-x-3"
                    >
                        <i class="fas fa-file-contract text-indigo-600"></i>
                        <span>Gestion des Contrats</span>
                    </h1>
                    <div v-if="canCreateContract" class="flex space-x-4">
                        <Link
                            :href="route('contracts.create')"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full shadow-lg hover:from-indigo-700 hover:to-indigo-600 transition duration-300 transform hover:scale-105"
                        >
                            <i class="fas fa-plus mr-2"></i>Nouveau Contrat
                        </Link>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div
                        class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4"
                    >
                        <div class="bg-green-100 p-3 rounded-full">
                            <i
                                class="fas fa-file-alt text-2xl text-green-600"
                            ></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Contrats</p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ totalContracts }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4"
                    >
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-home text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">
                                Propriétés Louées
                            </p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ rentedProperties }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4"
                    >
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i
                                class="fas fa-exclamation-triangle text-2xl text-yellow-600"
                            ></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">
                                Contrats Expirants
                            </p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ expiringContracts }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        Filtres avancés
                    </h2>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                    >
                        <div>
                            <label
                                for="filter-tenant"
                                class="block text-sm font-medium text-gray-700"
                                >Locataire</label
                            >
                            <select
                                id="filter-tenant"
                                v-model="filters.tenant"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Tous les locataires</option>
                                <option
                                    v-for="tenant in tenants"
                                    :key="tenant.id"
                                    :value="tenant.id"
                                >
                                    {{ tenant.first_name }}
                                    {{ tenant.last_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="filter-property"
                                class="block text-sm font-medium text-gray-700"
                                >Propriété</label
                            >
                            <select
                                id="filter-property"
                                v-model="filters.property"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Toutes les propriétés</option>
                                <option
                                    v-for="property in properties"
                                    :key="property.id"
                                    :value="property.id"
                                >
                                    {{ property.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="filter-start-date"
                                class="block text-sm font-medium text-gray-700"
                                >Date de début</label
                            >
                            <input
                                type="date"
                                id="filter-start-date"
                                v-model="filters.start_date"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <label
                                for="filter-end-date"
                                class="block text-sm font-medium text-gray-700"
                                >Date de fin</label
                            >
                            <input
                                type="date"
                                id="filter-end-date"
                                v-model="filters.end_date"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button
                            @click="applyFilters"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
                        >
                            <i class="fas fa-search mr-2"></i>Rechercher
                        </button>
                        <button
                            @click="resetFilters"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out"
                        >
                            <i class="fas fa-undo mr-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Contracts List -->
                <div
                    v-if="!filteredContracts.length"
                    class="flex items-center justify-center h-64 bg-white rounded-xl shadow-md"
                >
                    <div class="text-center">
                        <i
                            class="fas fa-file-contract text-6xl mb-4 text-indigo-300"
                        ></i>
                        <p class="text-xl text-gray-700">
                            Aucun contrat trouvé
                        </p>
                    </div>
                </div>

                <div v-else>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            ID
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
                                            Locataire
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Propriété
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Date de début
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Date de fin
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="contract in paginatedContracts"
                                        :key="contract.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ contract.id }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ contract.contract_type }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ contract.tenant.first_name }}
                                            {{ contract.tenant.last_name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ contract.property?.name || 'Propriété supprimée' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{
                                                formatDate(contract.start_date)
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ formatDate(contract.end_date) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'contracts.show',
                                                        contract.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900 mr-2"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'contracts.edit',
                                                        contract.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900 mr-2"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </Link>
                                            <button
                                                @click="
                                                    openDeleteModal(contract)
                                                "
                                                class="text-red-600 hover:text-red-900"
                                            >
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
                        <nav
                            class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination"
                        >
                            <button
                                @click="previousPage"
                                :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                            >
                                <span class="sr-only">Précédent</span>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                >{{ currentPage }} / {{ totalPages }}</span
                            >
                            <button
                                @click="nextPage"
                                :disabled="currentPage === totalPages"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                            >
                                <span class="sr-only">Suivant</span>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Modal de confirmation de suppression -->
                <Modal :show="showDeleteModal" @close="closeDeleteModal">
                    <div class="p-6 bg-white rounded-lg shadow-xl">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Confirmer la suppression
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Êtes-vous sûr de vouloir supprimer ce contrat ?
                            Cette action est irréversible.
                        </p>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button
                                @click="closeDeleteModal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out"
                            >
                                Annuler
                            </button>
                            <button
                                @click="confirmDelete"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out"
                            >
                                Supprimer
                            </button>
                        </div>
                    </div>
                </Modal>

                <Modal :show="showSuccessModal" @close="closeSuccessModal">
            <div class="p-6 bg-white rounded-lg shadow-xl">
                <h3 class="text-lg font-medium text-green-indigo mb-4">
                    Opération réussie
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    {{ successMessage }}
                </p>
                <div class="mt-6 flex justify-end">
                    <button
                        @click="closeSuccessModal"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </Modal>

        <Modal :show="showErrorModal" @close="closeErrorModal">
            <div class="p-6 bg-white rounded-lg shadow-xl">
                <h3 class="text-lg font-medium text-red-600 mb-4">
                    Erreur
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    {{ errorMessage }}
                </p>
                <div class="mt-6 flex justify-end">
                    <button
                        @click="closeErrorModal"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out"
                    >
                        Fermer
                    </button>
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
const contracts = ref(props.contracts.data || []);
const tenants = ref(props.tenants || []);
const properties = ref(props.properties || []);
const user = props.auth.user;
const userRoles = computed(() => props.auth?.user?.roles?.map(role => role.name) ?? []);

const showDeleteModal = ref(false);
const contractToDelete = ref(null);
const currentPage = ref(props.contracts.current_page || 1);
const totalPages = ref(props.contracts.last_page || 1);
const itemsPerPage = 10;

const showSuccessModal = ref(false);
const showErrorModal = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const filters = ref({
    tenant: "",
    property: "",
    start_date: "",
    end_date: "",
});

// Transformer les computed en refs
const totalContracts = ref(contracts.value.length);
const rentedProperties = ref(0);
const expiringContracts = ref(0);

const canCreateContract = computed(() => {
    return (
        userRoles.value.includes("super_admin") ||
        userRoles.value.includes("admin_entreprise")
    );
});

const filteredContracts = computed(() => {
    return contracts.value.filter((contract) => {
        const matchesTenant =
            !filters.value.tenant ||
            contract.tenant.id === filters.value.tenant;
        const matchesProperty =
            !filters.value.property ||
            contract.property?.id === filters.value.property;
        const matchesStartDate =
            !filters.value.start_date ||
            new Date(contract.start_date).toISOString().split("T")[0] ===
                filters.value.start_date;
        const matchesEndDate =
            !filters.value.end_date ||
            new Date(contract.end_date).toISOString().split("T")[0] ===
                filters.value.end_date;
        return (
            matchesTenant &&
            matchesProperty &&
            matchesStartDate &&
            matchesEndDate
        );
    });
});

const paginatedContracts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredContracts.value.slice(start, end);
});

onMounted(() => {
    updateStatistics();
});

const updateStatistics = () => {
    totalContracts.value = contracts.value.length;

    const rentedSet = new Set();
    contracts.value.forEach((contract) => {
        if (contract.property) {
            rentedSet.add(contract.property.id);
        }
    });
    rentedProperties.value = rentedSet.size;

    const oneMonthFromNow = new Date();
    oneMonthFromNow.setMonth(oneMonthFromNow.getMonth() + 1);
    expiringContracts.value = contracts.value.filter((contract) => {
        const endDate = new Date(contract.end_date);
        return endDate <= oneMonthFromNow && endDate >= new Date();
    }).length;
};

const applyFilters = () => {
    currentPage.value = 1;
};

const resetFilters = () => {
    filters.value = {
        tenant: "",
        property: "",
        start_date: "",
        end_date: "",
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

const openDeleteModal = (contract) => {
    contractToDelete.value = contract;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    contractToDelete.value = null;
};

const confirmDelete = () => {
    if (contractToDelete.value) {
        const contractId = contractToDelete.value.id;
        router.delete(route("contracts.destroy", contractId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                closeDeleteModal();
                contracts.value = contracts.value.filter(
                    (c) => c && c.id !== contractId
                );
                updateStatistics();
                successMessage.value = "Le contrat a été supprimé avec succès.";
                showSuccessModal.value = true;
            },
            onError: (errors) => {
                closeDeleteModal();
                errorMessage.value = "Une erreur est survenue lors de la suppression du contrat.";
                showErrorModal.value = true;
            },
        });
    }
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
    successMessage.value = '';
};

const closeErrorModal = () => {
    showErrorModal.value = false;
    errorMessage.value = '';
};

const formatDate = (dateString) => {
    if (!dateString || dateString === "N/A") return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    const date = new Date(dateString);
    return isNaN(date)
        ? "Invalid Date"
        : date.toLocaleDateString("fr-FR", options);
};
</script>

<style scoped>
.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}

.from-blue-50 {
    --tw-gradient-from: #eff6ff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(239, 246, 255, 0));
}

.to-indigo-100 {
    --tw-gradient-to: #e0e7ff;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}

.transition {
    transition-property: background-color, border-color, color, fill, stroke,
        opacity, box-shadow, transform, filter, backdrop-filter;
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
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
         0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
         0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>
