<template>
    <AppLayout title="Versements des Bailleurs">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <SummaryCards :summaryStats="summaryStats" />

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 sm:p-10 bg-gradient-to-r from-indigo-600 to-indigo-700">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <div class="text-white mb-4 sm:mb-0">
                            <h2 class="text-3xl font-bold flex items-center">
                                <i class="bi bi-graph-up-arrow text-white mr-3 text-4xl"></i>
                                Versements des Bailleurs
                            </h2>
                            <p class="mt-2 text-blue-100 font-light">
                                Suivi financier et gestion des paiements
                            </p>
                        </div>
                        <button
                            @click="goToCreatePayout"
                            class="px-6 py-3 bg-white text-indigo-900 rounded-full shadow-lg hover:bg-gray-100 transition-all duration-200 flex items-center font-semibold text-sm"
                        >
                            <i class="bi bi-plus-circle-fill mr-2"></i>
                            Nouveau versement
                        </button>
                    </div>
                </div>

                <PayoutFilters
                    :filters="filters"
                    :landlords="landlords"
                    :viewMode="viewMode"
                    @apply-filters="applyFilters"
                    @download="downloadPayoutsList"
                    @change-view="changeViewMode"
                />

                <div class="p-6 sm:p-10">
                    <div v-if="filteredPayouts.length === 0" class="text-center py-10">
                        <i class="bi bi-inbox text-5xl text-gray-400"></i>
                        <p class="mt-4 text-xl font-semibold text-gray-500">Aucun versement</p>
                        <p class="mt-2 text-gray-400">
                            Il n'y a pas de versements correspondant à vos critères de recherche.
                        </p>
                    </div>
                    <PayoutGrid
                        v-else-if="viewMode === 'grid'"
                        :payouts="paginatedPayouts"
                        @view="viewPayout"
                        @edit="editPayout"
                        @delete="confirmDelete"
                    />
                    <PayoutList
                        v-else
                        :payouts="paginatedPayouts"
                        :tableHeaders="tableHeaders"
                        @view="viewPayout"
                        @edit="editPayout"
                        @delete="confirmDelete"
                    />
                </div>

                <Pagination
                    v-if="filteredPayouts.length > 0"
                    :currentPage="currentPage"
                    :totalPages="totalPages"
                    :totalItems="filteredPayouts.length"
                    :itemsPerPage="itemsPerPage"
                    @change-page="changePage"
                />
            </div>
        </div>

        <DeletePayoutModal
            :show="showDeleteModal"
            :payout-id="payoutToDelete?.id"
            @close="showDeleteModal = false"
            @confirm="deletePayout"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import SummaryCards from "@/Components/LandlordPayouts/SummaryCards.vue";
import PayoutFilters from "@/Components/LandlordPayouts/PayoutFilters.vue";
import PayoutGrid from "@/Components/LandlordPayouts/PayoutGrid.vue";
import PayoutList from "@/Components/LandlordPayouts/PayoutList.vue";
import Pagination from "@/Components/LandlordPayouts/Pagination.vue";
import DeletePayoutModal from "@/Components/LandlordPayouts/DeletePayoutModal.vue";

const { props } = usePage();
const payouts = ref(props.payouts || []);
const landlords = ref(props.landlords || []);
const showDeleteModal = ref(false);
const payoutToDelete = ref(null);
const viewMode = ref("grid");
const currentPage = ref(1);
const itemsPerPage = 12;

const filters = ref({
    landlord: "",
    dateRange: "all",
});

const summaryStats = ref([
    {
        title: "Total des versements",
        value: 0,
        description: "Montant total versé aux bailleurs",
    },
    {
        title: "Versements ce mois",
        value: 0,
        description: "Montant total versé ce mois-ci",
    },
    {
        title: "Nombre de versements",
        value: 0,
        description: "Nombre total de versements effectués",
    },
]);

const tableHeaders = [
    { key: "landlord", label: "Bailleur" },
    { key: "amount", label: "Montant" },
    { key: "transaction_date", label: "Date de transaction" },
    { key: "payment_method", label: "Méthode de paiement" },
    { key: "status", label: "Statut" },
];

const filteredPayouts = computed(() => {
    return payouts.value.filter((payout) => {
        const landlordMatch =
            !filters.value.landlord ||
            (payout.landlord &&
                payout.landlord.id === parseInt(filters.value.landlord));

        let dateMatch = true;
        if (filters.value.dateRange !== "all") {
            const payoutDate = new Date(payout.transaction_date);
            const now = new Date();
            switch (filters.value.dateRange) {
                case "thisMonth":
                    dateMatch =
                        payoutDate.getMonth() === now.getMonth() &&
                        payoutDate.getFullYear() === now.getFullYear();
                    break;
                case "lastMonth":
                    const lastMonth = new Date(
                        now.getFullYear(),
                        now.getMonth() - 1
                    );
                    dateMatch =
                        payoutDate.getMonth() === lastMonth.getMonth() &&
                        payoutDate.getFullYear() === lastMonth.getFullYear();
                    break;
                case "thisYear":
                    dateMatch = payoutDate.getFullYear() === now.getFullYear();
                    break;
            }
        }

        return landlordMatch && dateMatch;
    });
});

const paginatedPayouts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredPayouts.value.slice(start, end);
});

const totalPages = computed(() =>
    Math.ceil(filteredPayouts.value.length / itemsPerPage)
);

const displayedPages = computed(() => {
    const range = 2;
    let start = Math.max(1, currentPage.value - range);
    let end = Math.min(totalPages.value, currentPage.value + range);

    if (end - start + 1 < range * 2 + 1) {
        if (start === 1) {
            end = Math.min(start + range * 2, totalPages.value);
        } else {
            start = Math.max(end - range * 2, 1);
        }
    }

    return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const applyFilters = () => {
    currentPage.value = 1;
};

const viewPayout = (payout) => {
    router.get(route("landlord-payouts.show", payout.id));
};

const editPayout = (payout) => {
    router.get(route("landlord-payouts.edit", payout.id));
};

const confirmDelete = (payout) => {
    payoutToDelete.value = payout;
    showDeleteModal.value = true;
};

const deletePayout = (payoutId) => {
    router.delete(route("landlord-payouts.destroy", payoutId), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            payoutToDelete.value = null;
        },
    });
};

const goToCreatePayout = () => {
    router.get(route('landlord-payouts.create'));
};

const downloadPayoutsList = () => {
    // Implement download logic
    console.log("Downloading payouts list");
    // You can use a library like jspdf and jspdf-autotable to generate a PDF
    // or create a CSV file using the filteredPayouts data
};

const updateSummaryStats = () => {
    const totalAmount = payouts.value.reduce(
        (sum, payout) => sum + parseFloat(payout.amount),
        0
    );
    const thisMonth = new Date().getMonth();
    const thisYear = new Date().getFullYear();
    const thisMonthAmount = payouts.value.reduce((sum, payout) => {
        const payoutDate = new Date(payout.transaction_date);
        if (
            payoutDate.getMonth() === thisMonth &&
            payoutDate.getFullYear() === thisYear
        ) {
            return sum + parseFloat(payout.amount);
        }
        return sum;
    }, 0);

    summaryStats.value[0].value = totalAmount;
    summaryStats.value[1].value = thisMonthAmount;
    summaryStats.value[2].value = payouts.value.length;
};

const changeViewMode = (mode) => {
    viewMode.value = mode;
};

onMounted(() => {
    updateSummaryStats();
});

// Watch for changes in payouts and update summary stats
watch(payouts, () => {
    updateSummaryStats();
});

// Watch for changes in filters and reset to page 1
watch(
    filters,
    () => {
        currentPage.value = 1;
    },
    { deep: true }
);
</script>
