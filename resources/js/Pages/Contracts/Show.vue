<template>
    <AppLayout :title="`Contrat #${contract.id}`">
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- En-tête du contrat -->
                <div class="bg-white shadow-xl rounded-lg overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                                    <i class="bi-file-earmark-text text-indigo-600 text-3xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">
                                        Dossier de location N°{{ contract.id }}
                                    </h2>
                                    <p class="text-indigo-200">
                                        {{ contract.contract_type }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Barre de statut -->
                    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-500 mr-2">Statut:</span>
                            <span :class="[
                                'px-2 py-1 text-xs font-bold rounded',
                                contract.status === 'Actif' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-gray-800',
                            ]">
                                {{ contract.status }}
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <GenerateContract />
                            <GenerateReceipt />
                        </div>
                    </div>
                </div>

                <!-- Informations du contrat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <ContractInfoCard
                        title="Informations générales"
                        icon="bi-info-circle"
                        :items="generalInfo"
                        :status="contract.status"
                    />
                    <ContractInfoCard
                        title="Informations financières"
                        icon="bi-currency-dollar"
                        :items="financialInfo"
                    />
                    <ContractInfoCard
                        title="Informations du locataire"
                        icon="bi-person"
                        :items="tenantInfo"
                    />
                    <ContractInfoCard
                        title="Informations de la propriété"
                        icon="bi-house-door"
                        :items="propertyInfo"
                    />
                </div>

                <!-- Documents liés -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Documents liés</h3>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200">
                            <li v-for="doc in relatedDocuments" :key="doc.id" class="py-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <i :class="['text-gray-400 mr-3', doc.icon]"></i>
                                    <span class="text-sm font-medium text-gray-900">{{ doc.name }}</span>
                                </div>
                                <button @click="downloadDocument(doc)" class="text-sm border border-gray-300 text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md">
                                    <i class="bi-download mr-2"></i> Télécharger
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4">
                    <button @click="editContract" class="border bg-indigo-600 hover:border-indigo-600 text-white hover:bg-white hover:text-indigo-600 px-4 py-2 rounded-md">
                        <i class="bi-pencil mr-2"></i> Modifier
                    </button>
                    <button @click="confirmDelete" class="border border-red-300 text-red-700 hover:bg-red-50 px-4 py-2 rounded-md">
                        <i class="bi-trash mr-2"></i> Supprimer le contrat
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation de suppression -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6 bg-white rounded-lg shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
                <p class="text-sm text-gray-500 mb-4">Êtes-vous sûr de vouloir supprimer ce contrat ? Cette action est irréversible.</p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="closeDeleteModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">Annuler</button>
                    <button @click="deleteContract" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">Supprimer</button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ContractInfoCard from "@/Components/ContractInfoCard.vue";
import Modal from "@/Components/Modal.vue";
import GenerateContract from "@/Components/GenerateContract.vue";
import GenerateReceipt from "@/Components/GenerateReceipt.vue";

const { props } = usePage();
const contract = ref(props.contract);
const totalCommission = ref(props.totalCommission);

const showDeleteModal = ref(false);

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const deleteContract = () => {
    router.delete(route("contracts.destroy", contract.value.id), {
        onSuccess: () => router.visit(route("contracts.index")),
    });
};

const editContract = () => {
    router.visit(route("contracts.edit", contract.value.id));
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(amount);
};

const calculateDuration = (start, end) => {
    const startDate = new Date(start);
    const endDate = new Date(end);
    const diffTime = Math.abs(endDate - startDate);
    const diffMonths = Math.ceil(diffTime / (1000 * 60 * 60 * 24 * 30));
    return diffMonths;
};

const calculateTotalRevenue = () => {
    const monthlyRent = contract.value.rent_amount;
    const duration = calculateDuration(
        contract.value.start_date,
        contract.value.end_date
    );
    return monthlyRent * duration;
};

const generalInfo = computed(() => [
    {
        label: "Date de début",
        value: formatDate(contract.value.start_date),
        icon: "bi-calendar-event",
    },
    {
        label: "Date de fin",
        value: formatDate(contract.value.end_date),
        icon: "bi-calendar-check",
    },
    {
        label: "Durée",
        value: `${calculateDuration(
            contract.value.start_date,
            contract.value.end_date
        )} mois`,
        icon: "bi-clock-history",
    },
    {
        label: "Numéro de dossier",
        value: contract.value.file_number,
        icon: "bi-folder",
        highlight: true,
    },
]);

const financialInfo = computed(() => [
    {
        label: "Montant du loyer",
        value: formatCurrency(contract.value.rent_amount),
        icon: "bi-cash",
        highlight: true,
    },
    {
        label: "Caution",
        value: formatCurrency(contract.value.caution_amount),
        icon: "bi-safe",
    },
    {
        label: "Commission totale",
        value: formatCurrency(totalCommission.value),
        icon: "bi-graph-up",
        highlight: true,
    },
    {
        label: "Revenus totaux",
        value: formatCurrency(calculateTotalRevenue()),
        icon: "bi-graph-up",
        highlight: true,
    },
]);

const tenantInfo = computed(() => [
    {
        label: "Nom",
        value: `${contract.value.tenant.first_name} ${contract.value.tenant.last_name}`,
        icon: "bi-person",
    },
    { label: "Email", value: contract.value.tenant.email, icon: "bi-envelope" },
    {
        label: "Téléphone",
        value: contract.value.tenant.phone_number || "N/A",
        icon: "bi-phone",
    },
]);

const propertyInfo = computed(() => [
    {
        label: "Nom",
        value: contract.value.property.name,
        icon: "bi-house-door",
    },
    {
        label: "Adresse",
        value: contract.value.property.address,
        icon: "bi-geo-alt",
    },
    {
        label: "Type",
        value: contract.value.property.property_type,
        icon: "bi-building",
    },
]);

const relatedDocuments = ref([
    { id: 1, name: "Contrat signé", icon: "bi-file-earmark-pdf" },
    { id: 2, name: "État des lieux", icon: "bi-file-earmark-text" },
    { id: 3, name: "Attestation d'assurance", icon: "bi-file-earmark-check" },
]);

const downloadContract = () => {
    console.log("Téléchargement du contrat");
    // Ajoutez la logique de téléchargement du contrat ici
};

const downloadReceipt = () => {
    console.log("Téléchargement du reçu");
    // Ajoutez la logique de téléchargement du reçu ici
};

const print = () => {
    window.print();
};

const downloadDocument = (doc) => {
    console.log(`Téléchargement du document: ${doc.name}`);
    // Ajoutez la logique de téléchargement du document ici
};

const sendEmail = () => {
    console.log("Envoi d'un e-mail");
    // Ajoutez la logique d'envoi de l'e-mail ici
};
</script>

<style scoped>
@import "bootstrap-icons/font/bootstrap-icons.css";

@media print {
    .bg-gray-100 {
        background-color: white;
    }

    .shadow-xl,
    .shadow-lg {
        box-shadow: none;
    }

    .bg-gradient-to-r {
        background: white;
        color: black;
    }

    .text-white,
    .text-indigo-200 {
        color: black;
    }

    .bg-gray-50 {
        background-color: white;
    }

    .hidden {
        display: none;
    }
}
</style>
