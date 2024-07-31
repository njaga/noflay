<template>
    <AppLayout title="Compte Bailleur">
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-12">
                <LandlordProfile
                    :landlord="landlord"
                    :attachments="attachments"
                    @open-generate-mandat="openGenerateMandat"
                    @edit-landlord="editLandlord"
                    @confirm-delete-landlord="confirmDeleteLandlord"
                    @open-add-expense-modal="openAddExpenseModal"
                    @open-add-payment-modal="openAddPaymentModal"
                />
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mt-8">
                    <div class="col-span-1 md:col-span-2 lg:col-span-2 space-y-8">
                        <FinancialOverview :financialInfo="financialInfo" />
                        <RecentTransactions
                            :transactions="transactions"
                            @view-transaction="viewTransaction"
                            @edit-transaction="editTransaction"
                            @delete-transaction="confirmDeleteTransaction"
                        />
                    </div>
                    <PropertiesList
                        :landlord="landlord"
                        @confirm-delete-property="confirmDeleteProperty"
                        @add-property="addProperty"
                    />
                </div>
            </div>
        </div>

        <!-- Modals -->
        <GenerateMandat
            v-if="showGenerateMandat"
            :landlord="landlord"
            :properties="landlord.properties"
            :company="company"
            @close="showGenerateMandat = false"
        />
        <AddExpenseModal
            v-if="showAddExpenseModal"
            :landlord="landlord"
            @close="showAddExpenseModal = false"
            @saved="handleSavedExpense"
        />
        <AddPaymentModal
            v-if="showAddPaymentModal"
            :landlord="landlord"
            @close="showAddPaymentModal = false"
            @saved="handleSavedPayment"
        />
        <ViewTransactionModal
            v-if="showViewTransactionModal"
            :show="showViewTransactionModal"
            :transaction="selectedTransaction"
            @close="handleCloseViewTransactionModal"
        />
        <EditTransactionModal
            v-if="showEditTransactionModal"
            :show="showEditTransactionModal"
            :transaction="selectedTransaction"
            @close="handleCloseEditTransactionModal"
        />
        <NewConfirmationModal
            :show="showConfirmationModal"
            :title="confirmationTitle"
            :message="confirmationMessage"
            @confirm="handleConfirm"
            @close="handleCloseConfirmationModal"
        />
        <NotificationModal
            :show="showNotificationModal"
            :title="notificationTitle"
            :message="notificationMessage"
            :type="notificationType"
            @close="handleCloseNotificationModal"
        />
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import LandlordProfile from "@/Components/Landlords/LandlordProfile.vue";
import FinancialOverview from "@/Components/Landlords/FinancialOverview.vue";
import RecentTransactions from "@/Components/Landlords/RecentTransactions.vue";
import PropertiesList from "@/Components/Landlords/PropertiesList.vue";
import GenerateMandat from "@/Components/GenerateMandat.vue";
import AddExpenseModal from "@/Components/Landlords/AddExpenseModal.vue";
import AddPaymentModal from "@/Components/Landlords/AddPaymentModal.vue";
import ViewTransactionModal from "@/Components/ViewTransactionModal.vue";
import EditTransactionModal from "@/Components/EditTransactionModal.vue";
import NewConfirmationModal from "@/Components/NewConfirmationModal.vue";
import NotificationModal from "@/Components/NotificationModal.vue";

const { props } = usePage();
const landlord = ref(props.landlord || {});
const transactions = ref(props.transactions || []);
const financialInfo = ref(props.financialInfo || {});
const showGenerateMandat = ref(false);
const showAddExpenseModal = ref(false);
const showAddPaymentModal = ref(false);
const showConfirmationModal = ref(false);
const showNotificationModal = ref(false);
const showViewTransactionModal = ref(false);
const showEditTransactionModal = ref(false);
const selectedTransaction = ref(null);
const company = ref(props.company || {});

const attachments = ref([]);

onMounted(() => {
    if (landlord.value.attachments) {
        attachments.value = JSON.parse(landlord.value.attachments);
    }
});

const confirmationTitle = ref("");
const confirmationMessage = ref("");
let confirmationAction = null;
const notificationTitle = ref("");
const notificationMessage = ref("");
const notificationType = ref("");

function viewTransaction(transaction) {
    selectedTransaction.value = transaction;
    showViewTransactionModal.value = true;
}

function editTransaction(transaction) {
    selectedTransaction.value = transaction;
    showEditTransactionModal.value = true;
}

function confirmDeleteTransaction(transaction) {
    confirmationTitle.value = "Supprimer la transaction";
    confirmationMessage.value = `Êtes-vous sûr de vouloir supprimer la transaction du ${formatDate(transaction.transaction_date)} pour un montant de ${formatCurrency(transaction.amount)} ?`;
    confirmationAction = () => deleteTransaction(transaction.id);
    showConfirmationModal.value = true;
}

function deleteTransaction(id) {
    router.delete(route("landlord-transactions.destroy", id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            notificationTitle.value = "Supprimé avec succès";
            notificationMessage.value = "La transaction a été supprimée avec succès.";
            notificationType.value = "success";
            showNotificationModal.value = true;
            router.reload({ only: ["transactions", "financialInfo"] });
        },
        onError: () => {
            notificationTitle.value = "Échec de la suppression";
            notificationMessage.value = "La suppression de la transaction a échoué.";
            notificationType.value = "error";
            showNotificationModal.value = true;
        },
    });
}


function openGenerateMandat() {
    showGenerateMandat.value = true;
}

function openAddExpenseModal() {
    showAddExpenseModal.value = true;
}

function openAddPaymentModal() {
    showAddPaymentModal.value = true;
}

function confirmDeleteLandlord() {
    confirmationTitle.value = "Supprimer le bailleur";
    confirmationMessage.value = "Êtes-vous sûr de vouloir supprimer ce bailleur ? Cette action est irréversible.";
    confirmationAction = deleteLandlord;
    showConfirmationModal.value = true;
}

function confirmDeleteProperty(propertyId) {
    confirmationTitle.value = "Supprimer la propriété";
    confirmationMessage.value = "Êtes-vous sûr de vouloir supprimer cette propriété ? Cette action est irréversible.";
    confirmationAction = () => deleteProperty(propertyId);
    showConfirmationModal.value = true;
}

function handleConfirm() {
    if (confirmationAction) {
        confirmationAction();
        showConfirmationModal.value = false;
    }
}

function handleCloseConfirmationModal() {
    showConfirmationModal.value = false;
}

function handleCloseNotificationModal() {
    showNotificationModal.value = false;
}

function handleCloseViewTransactionModal() {
    showViewTransactionModal.value = false;
}

function handleCloseEditTransactionModal() {
    showEditTransactionModal.value = false;
}

function deleteLandlord() {
    router.delete(route("landlords.destroy", landlord.value.id), {
        onSuccess: () => {
            notificationTitle.value = "Supprimé avec succès";
            notificationMessage.value = "Le bailleur a été supprimé avec succès.";
            notificationType.value = "success";
            showNotificationModal.value = true;
            router.visit(route("landlords.index"));
        },
        onError: () => {
            notificationTitle.value = "Échec de la suppression";
            notificationMessage.value = "La suppression du bailleur a échoué.";
            notificationType.value = "error";
            showNotificationModal.value = true;
        },
    });
}

function deleteProperty(propertyId) {
    router.delete(route("properties.destroy", propertyId), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            notificationTitle.value = "Supprimé avec succès";
            notificationMessage.value = "La propriété a été supprimée avec succès.";
            notificationType.value = "success";
            showNotificationModal.value = true;
            router.reload({ only: ["landlord"] });
        },
        onError: () => {
            notificationTitle.value = "Échec de la suppression";
            notificationMessage.value = "La suppression de la propriété a échoué.";
            notificationType.value = "error";
            showNotificationModal.value = true;
        },
    });
}

function editLandlord() {
    router.get(route("landlords.edit", landlord.value.id));
}

function addProperty() {
    router.get(route("properties.create", { landlord_id: landlord.value.id }));
}

function handleSavedExpense() {
    showAddExpenseModal.value = false;
    notificationTitle.value = "Dépense ajoutée avec succès";
    notificationMessage.value = "La dépense a été enregistrée avec succès.";
    notificationType.value = "success";
    showNotificationModal.value = true;
    router.reload({ only: ["landlord", "transactions", "financialInfo"] });
}

function handleSavedPayment() {
    showAddPaymentModal.value = false;
    notificationTitle.value = "Paiement enregistré avec succès";
    notificationMessage.value = "Le paiement a été enregistré avec succès.";
    notificationType.value = "success";
    showNotificationModal.value = true;
    router.reload({ only: ["landlord", "transactions", "financialInfo"] });
}

function formatDate(dateString) {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
}

function formatCurrency(value) {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(value);
}
</script>

<style>
@import "bootstrap-icons/font/bootstrap-icons.css";
</style>
