<template>
    <AppLayout title="Gestion des Paiements">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center mb-8"
                >
                    <h1
                        class="text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0 flex items-center space-x-3"
                    >
                        <i
                            class="fas fa-file-invoice-dollar text-indigo-600"
                        ></i>
                        <span>Mensualités des locataires</span>
                    </h1>
                    <div v-if="canCreatePayment" class="flex space-x-4">
                        <Link
                            :href="route('payments.create')"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full shadow-lg hover:from-indigo-700 hover:to-indigo-600 transition duration-300 transform hover:scale-105"
                        >
                            <i class="fas fa-plus mr-2"></i>Nouveau Paiement
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
                                class="fas fa-dollar-sign text-2xl text-green-600"
                            ></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Paiements</p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ totalPayments }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4"
                    >
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-coins text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Montant Total</p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ totalAmount }} CFA
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
                                Paiements à Venir
                            </p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ upcomingPayments }}
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
                                for="filter-contract"
                                class="block text-sm font-medium text-gray-700"
                                >Contrat</label
                            >
                            <select
                                id="filter-contract"
                                v-model="filters.contract"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Tous les contrats</option>
                                <option
                                    v-for="contract in contracts"
                                    :key="contract.id"
                                    :value="contract.id"
                                >
                                    {{ contract.file_number }}
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

                <!-- Payments List -->
                <div
                    v-if="!filteredPayments.length"
                    class="flex items-center justify-center h-64 bg-white rounded-xl shadow-md"
                >
                    <div class="text-center">
                        <i
                            class="fas fa-file-invoice-dollar text-6xl mb-4 text-indigo-300"
                        ></i>
                        <p class="text-xl text-gray-700">
                            Aucun paiement trouvé
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
                                            Locataire
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Contrat
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Montant
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Date de Paiement
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
                                        v-for="payment in paginatedPayments"
                                        :key="payment.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ payment.id }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ payment.tenant?.first_name || 'N/A' }}
                                            {{ payment.tenant?.last_name || 'N/A' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ payment.contract?.file_number || 'N/A' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ payment.amount }} CFA
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{
                                                formatDate(payment.payment_date)
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'payments.show',
                                                        payment.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900 mr-2"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'payments.edit',
                                                        payment.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900 mr-2"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </Link>
                                            <button
                                                @click="
                                                    openDeleteModal(payment)
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
                            >
                                {{ currentPage }} / {{ totalPages }}
                            </span>
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
                            Êtes-vous sûr de vouloir supprimer ce paiement ?
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
const payments = ref(props.payments.data || []);
const tenants = ref(props.tenants || []);
const contracts = ref(props.contracts || []);
const user = props.auth.user;
const userRoles = computed(() => props.auth?.user?.roles?.map(role => role.name) ?? []);

const showDeleteModal = ref(false);
const paymentToDelete = ref(null);
const currentPage = ref(props.payments.current_page || 1);
const totalPages = ref(props.payments.last_page || 1);
const itemsPerPage = 10;

const filters = ref({
    tenant: "",
    contract: "",
    start_date: "",
    end_date: "",
});

const totalPayments = computed(() => payments.value.length);
const totalAmount = computed(() =>
    payments.value.reduce((sum, payment) => sum + parseFloat(payment.amount), 0)
);
const upcomingPayments = computed(() => {
    const contractPaymentsMap = new Map();

    contracts.value.forEach((contract) => {
        const monthYear = new Date(contract.start_date).toLocaleDateString(
            "fr-FR",
            { year: "numeric", month: "numeric" }
        );
        if (!contractPaymentsMap.has(monthYear)) {
            contractPaymentsMap.set(monthYear, {
                totalContracts: 0,
                paymentsMade: 0,
            });
        }
        contractPaymentsMap.get(monthYear).totalContracts += 1;
    });

    payments.value.forEach((payment) => {
        const monthYear = new Date(payment.payment_date).toLocaleDateString(
            "fr-FR",
            { year: "numeric", month: "numeric" }
        );
        if (contractPaymentsMap.has(monthYear)) {
            contractPaymentsMap.get(monthYear).paymentsMade += 1;
        }
    });

    let unpaidContracts = 0;
    contractPaymentsMap.forEach(({ totalContracts, paymentsMade }) => {
        unpaidContracts += totalContracts - paymentsMade;
    });

    return unpaidContracts;
});

onMounted(() => {
    // Initialize data if needed
});

const canCreatePayment = computed(() => {
    return (
        userRoles.value.includes("super_admin") ||
        userRoles.value.includes("admin_entreprise")
    );
});

const filteredPayments = computed(() => {
    return payments.value.filter((payment) => {
        const matchesTenant =
            !filters.value.tenant || payment.tenant.id === filters.value.tenant;
        const matchesContract =
            !filters.value.contract ||
            payment.contract.id === filters.value.contract;
        const matchesStartDate =
            !filters.value.start_date ||
            new Date(payment.payment_date).toISOString().split("T")[0] >=
                filters.value.start_date;
        const matchesEndDate =
            !filters.value.end_date ||
            new Date(payment.payment_date).toISOString().split("T")[0] <=
                filters.value.end_date;
        return (
            matchesTenant &&
            matchesContract &&
            matchesStartDate &&
            matchesEndDate
        );
    });
});

const paginatedPayments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredPayments.value.slice(start, end);
});

const applyFilters = () => {
    currentPage.value = 1;
};

const resetFilters = () => {
    filters.value = {
        tenant: "",
        contract: "",
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

const openDeleteModal = (payment) => {
    paymentToDelete.value = payment;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    paymentToDelete.value = null;
};

const confirmDelete = () => {
    if (paymentToDelete.value) {
        router.delete(route("payments.destroy", paymentToDelete.value.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                payments.value = payments.value.filter(
                    (p) => p.id !== paymentToDelete.value.id
                );
            },
        });
    }
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
