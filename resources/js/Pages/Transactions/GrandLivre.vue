<template>
    <AppLayout title="Grand Livre Caisse">
        <div class="grand-livre-container">
            <!-- Filtres -->
            <div
                class="filters-container mb-6 p-4 bg-white rounded-lg shadow-md"
            >
                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                    Grand livre de caisse
                </h1>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label
                            for="type"
                            class="block text-sm font-medium text-gray-700"
                            >Type</label
                        >
                        <select
                            v-model="filters.type"
                            id="type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <option value="">Tous les types</option>
                            <option value="PAYMENT">Paiement</option>
                            <option value="EXPENSE">Dépense</option>
                            <option value="DEPOSIT">Caution</option>
                            <option value="REFUND">Remboursement</option>
                            <option value="LANDLORD_PAYOUT">
                                Versement au bailleur
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="date_from"
                            class="block text-sm font-medium text-gray-700"
                            >Date de début</label
                        >
                        <input
                            type="date"
                            v-model="filters.date_from"
                            id="date_from"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        />
                    </div>
                    <div>
                        <label
                            for="date_to"
                            class="block text-sm font-medium text-gray-700"
                            >Date de fin</label
                        >
                        <input
                            type="date"
                            v-model="filters.date_to"
                            id="date_to"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        />
                    </div>
                    <div class="flex items-end">
                        <button
                            @click="toggleAdvancedFilters"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105"
                        >
                            <i
                                class="bi"
                                :class="
                                    showAdvancedFilters
                                        ? 'bi-eye-slash'
                                        : 'bi-eye'
                                "
                            ></i>
                            <span class="ml-2">{{
                                showAdvancedFilters ? "Masquer" : "Afficher"
                            }}</span>
                        </button>
                    </div>
                </div>

                <!-- Filtres avancés -->
                <div
                    v-if="showAdvancedFilters"
                    class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4"
                >
                    <div>
                        <label
                            for="landlord"
                            class="block text-sm font-medium text-gray-700"
                            >Bailleur</label
                        >
                        <select
                            v-model="filters.landlord_id"
                            id="landlord"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <option value="">Tous les bailleurs</option>
                            <option
                                v-for="landlord in landlords"
                                :key="landlord.id"
                                :value="landlord.id"
                            >
                                {{ landlord.first_name }}
                                {{ landlord.last_name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="tenant"
                            class="block text-sm font-medium text-gray-700"
                            >Locataire</label
                        >
                        <select
                            v-model="filters.tenant_id"
                            id="tenant"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <option value="">Tous les locataires</option>
                            <option
                                v-for="tenant in tenants"
                                :key="tenant.id"
                                :value="tenant.id"
                            >
                                {{ tenant.first_name }} {{ tenant.last_name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="property"
                            class="block text-sm font-medium text-gray-700"
                            >Propriété</label
                        >
                        <select
                            v-model="filters.property_id"
                            id="property"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
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
                </div>

                <div class="mt-4 flex justify-end space-x-4">
                    <button
                        @click="applyFilters"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
                    >
                        Appliquer les filtres
                    </button>
                    <TransactionsExport :filters="filters" />
                </div>
            </div>

            <!-- Affichage des transactions -->
            <div
                v-if="
                    transactions &&
                    transactions.data &&
                    transactions.data.length > 0
                "
            >
                <!-- Version desktop : tableau -->
                <div
                    class="hidden md:block overflow-x-auto bg-white rounded-lg shadow"
                >
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    v-for="column in columns"
                                    :key="column.key"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ column.label }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="transaction in transactions.data"
                                :key="transaction.id"
                                class="hover:bg-gray-50 transition-colors duration-200"
                            >
                                <td
                                    v-for="column in columns"
                                    :key="column.key"
                                    class="px-6 py-4 whitespace-nowrap"
                                >
                                    <div
                                        v-if="column.key === 'type'"
                                        :class="getTypeClass(transaction.type)"
                                    >
                                        {{
                                            getTransactionType(transaction.type)
                                        }}
                                    </div>
                                    <div
                                        v-else-if="column.key === 'amount'"
                                        :class="{
                                            'text-green-600':
                                                transaction.amount > 0,
                                            'text-red-600':
                                                transaction.amount < 0,
                                        }"
                                    >
                                        {{
                                            formatCurrency(
                                                transaction.amount ||
                                                    transaction.total_amount
                                            )
                                        }}
                                    </div>
                                    <div v-else-if="column.key === 'date'">
                                        {{ formatDate(transaction) }}
                                    </div>
                                    <div v-else>
                                        {{
                                            column.format
                                                ? column.format(
                                                      transaction[column.key]
                                                  )
                                                : transaction[column.key]
                                        }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button
                                        @click="openModal(transaction)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                        Voir
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Version mobile : cards -->
                <div class="md:hidden space-y-4">
                    <div
                        v-for="transaction in transactions.data"
                        :key="transaction.id"
                        class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200"
                    >
                        <div class="flex justify-between items-center mb-2">
                            <span
                                :class="getTypeClass(transaction.type)"
                                class="text-xs font-semibold rounded-full px-2 py-1"
                            >
                                {{ getTransactionType(transaction.type) }}
                            </span>
                            <span class="text-sm text-gray-500">{{
                                formatDate(transaction)
                            }}</span>
                        </div>
                        <div class="mb-2">
                            <p class="font-semibold">
                                {{ transaction.description }}
                            </p>
                            <p
                                :class="{
                                    'text-green-600':
                                        (transaction.amount ||
                                            transaction.total_amount) > 0,
                                    'text-red-600':
                                        (transaction.amount ||
                                            transaction.total_amount) < 0,
                                }"
                            >
                                {{
                                    formatCurrency(
                                        transaction.amount ||
                                            transaction.total_amount
                                    )
                                }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p>
                                Bailleur:
                                {{ transaction.landlord?.first_name }}
                                {{ transaction.landlord?.last_name }}
                            </p>
                            <p>
                                Locataire: {{ transaction.tenant?.first_name }}
                                {{ transaction.tenant?.last_name }}
                            </p>
                            <p>Propriété: {{ transaction.property?.name }}</p>
                        </div>
                        <button
                            @click="openModal(transaction)"
                            class="mt-2 text-indigo-600 hover:text-indigo-800"
                        >
                            Voir les détails
                        </button>
                    </div>
                </div>
            </div>

            <!-- Nouvelle section pour afficher l'icône et le message lorsqu'il n'y a pas de transactions -->
            <div v-else class="text-center py-8 bg-gray-50 rounded-lg shadow">
                <CreditCard class="mx-auto h-12 w-12 text-gray-400 mb-4" />
                <p class="text-gray-500 text-lg font-medium">Aucune transaction trouvée</p>

            </div>

            <!-- Pagination -->
            <div v-if="transactions && transactions.meta" class="mt-6">
                <Pagination
                    :data="transactions"
                    @pagination-change-page="changePage"
                />
            </div>

            <!-- Modal pour les détails de la transaction -->
            <TransitionRoot appear :show="isModalOpen" as="template">
                <Dialog as="div" @close="closeModal" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div
                            class="fixed inset-0 bg-black/30 backdrop-blur-sm"
                        />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div
                            class="flex min-h-full items-center justify-center p-4 text-center"
                        >
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                >
                                    <DialogTitle
                                        as="h3"
                                        class="text-lg font-semibold leading-6 text-gray-900 mb-4"
                                    >
                                        Détails de la transaction
                                    </DialogTitle>
                                    <div class="mt-2 space-y-4">
                                        <div
                                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500"
                                                >Date</span
                                            >
                                            <span
                                                class="text-sm text-gray-900"
                                                >{{
                                                    formatDate(
                                                        selectedTransaction
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500"
                                                >Type</span
                                            >
                                            <span
                                                class="text-sm text-gray-900"
                                                >{{
                                                    getTransactionType(
                                                        selectedTransaction.type
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500"
                                                >Montant</span
                                            >
                                            <span
                                                class="text-sm"
                                                :class="{
                                                    'text-green-600':
                                                        (selectedTransaction.amount ||
                                                            selectedTransaction.total_amount) >
                                                        0,
                                                    'text-red-600':
                                                        (selectedTransaction.amount ||
                                                            selectedTransaction.total_amount) <
                                                        0,
                                                }"
                                            >
                                                {{
                                                    formatCurrency(
                                                        selectedTransaction.amount ||
                                                            selectedTransaction.total_amount
                                                    )
                                                }}
                                            </span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500"
                                                >Description</span
                                            >
                                            <span
                                                class="text-sm text-gray-900"
                                                >{{
                                                    selectedTransaction.description
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500"
                                                >Bailleur</span
                                            >
                                            <span
                                                class="text-sm text-gray-900"
                                                >{{
                                                    selectedTransaction.landlord
                                                        ? `${selectedTransaction.landlord.first_name}
                                                ${selectedTransaction.landlord.last_name}`
                                                        : "-"
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500"
                                                >Locataire</span
                                            >
                                            <span
                                                class="text-sm text-gray-900"
                                                >{{
                                                    selectedTransaction.tenant
                                                        ? `${selectedTransaction.tenant.first_name}
                                                ${selectedTransaction.tenant.last_name}`
                                                        : "-"
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500"
                                                >Propriété</span
                                            >
                                            <span
                                                class="text-sm text-gray-900"
                                                >{{
                                                    selectedTransaction.property
                                                        ? selectedTransaction
                                                              .property.name
                                                        : "-"
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            v-if="
                                                selectedTransaction.type ===
                                                'REFUND'
                                            "
                                            class="flex justify-between items-center bg-gray-50 p-3 rounded-lg"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500"
                                                >Raison du remboursement</span
                                            >
                                            <span
                                                class="text-sm text-gray-900"
                                                >{{
                                                    getRefundReason(
                                                        selectedTransaction.refund_reason
                                                    )
                                                }}</span
                                            >
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 transition-colors duration-200"
                                            @click="closeModal"
                                        >
                                            Fermer
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, onMounted, watch, reactive } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import TransactionsExport from "@/Components/TransactionsExport.vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useMediaQuery } from "@vueuse/core";
import { CreditCard } from "lucide-vue-next";

const page = usePage();

const props = defineProps({
    transactions: Object,
    filters: Object,
    landlords: Array,
    tenants: Array,
    properties: Array,
});

const transactions = ref(props.transactions || { data: [] });
const isLoading = ref(false);
const errorMessage = ref("");

const showAdvancedFilters = ref(false);
const isMobile = useMediaQuery("(max-width: 768px)");

const toggleAdvancedFilters = () => {
    showAdvancedFilters.value = !showAdvancedFilters.value;
};

const columns = [
    { key: "date", label: "Date", format: (value) => formatDate(value) },
    { key: "type", label: "Type" },
    { key: "description", label: "Description" },
    { key: "amount", label: "Montant" },
];

const filters = reactive({
    type: props.filters?.type || "",
    date_from: props.filters?.date_from || "",
    date_to: props.filters?.date_to || "",
    landlord_id: props.filters?.landlord_id || "",
    tenant_id: props.filters?.tenant_id || "",
    property_id: props.filters?.property_id || "",
});

const isModalOpen = ref(false);
const selectedTransaction = ref({});

const openModal = (transaction) => {
    selectedTransaction.value = transaction;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const getTransactionType = (type) => {
    const types = {
        PAYMENT: "Paiement",
        EXPENSE: "Dépense",
        DEPOSIT: "Caution",
        REFUND: "Remboursement",
        LANDLORD_PAYOUT: "Versement au bailleur",
        payout: "Versement au bailleur",
    };
    return types[type] || type;
};

const getRefundReason = (reason) => {
    const reasons = {
        caution: "Remboursement de caution",
        overpayment: "Trop-perçu de loyer",
        charges: "Régularisation des charges",
        repairs: "Remboursement de frais de réparations",
        other: "Autre remboursement",
    };
    return reasons[reason] || reason;
};

const getTypeClass = (type) => {
    const classes = {
        PAYMENT: "bg-green-100 text-green-800",
        EXPENSE: "bg-red-100 text-red-800",
        DEPOSIT: "bg-blue-100 text-blue-800",
        REFUND: "bg-yellow-100 text-yellow-800",
        LANDLORD_PAYOUT: "bg-purple-100 text-purple-800",
    };
    return `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
        classes[type] || "bg-gray-100 text-gray-800"
    }`;
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(value);
};

const formatDate = (transaction) => {
    const dateString = transaction.date || transaction.transaction_date;
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
};

const applyFilters = () => {
    isLoading.value = true;
    errorMessage.value = "";
    router.get("/transactions/grand-livre", filters, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            transactions.value = page.props.transactions;
            isLoading.value = false;
        },
        onError: (errors) => {
            console.error("Erreur lors de l'application des filtres:", errors);
            errorMessage.value =
                "Une erreur est survenue lors du chargement des transactions. Veuillez réessayer.";
            isLoading.value = false;
        },
    });
};

const changePage = (page) => {
    router.get(
        "/transactions/grand-livre",
        { ...filters, page: page },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                transactions.value = page.props.transactions;
            },
            onError: (errors) => {
                console.error("Erreur lors du changement de page:", errors);
                errorMessage.value =
                    "Une erreur est survenue lors du changement de page. Veuillez réessayer.";
            },
        }
    );
};

onMounted(() => {
    if (!transactions.value?.data?.length) {
        applyFilters();
    }
});

watch(
    filters,
    () => {
        applyFilters();
    },
    { deep: true }
);
</script>

<style scoped>
.grand-livre-container {
    @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8;
}

.filters-container {
    @apply transition-all duration-300 ease-in-out;
}

.filters-container:hover {
    @apply shadow-lg;
}

table {
    @apply border-collapse table-auto w-full;
}

th {
    @apply bg-gray-50 border-b border-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
}

td {
    @apply px-6 py-4 whitespace-nowrap text-sm text-gray-500;
}

tr:hover td {
    @apply bg-gray-50;
}
</style>
