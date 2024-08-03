<template>
    <AppLayout title="Détails du Contrat Archivé">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-indigo-900 mb-4">
                        Détails du Contrat Archivé
                    </h1>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-700 mb-2">
                                Contrat
                            </h2>
                            <p class="text-gray-600">
                                <strong>ID :</strong> {{ contract.id }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Type :</strong> {{ contract.contract_type }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Date de début :</strong> {{ formatDate(contract.start_date) }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Date de fin :</strong> {{ formatDate(contract.end_date) }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Numéro de dossier :</strong> {{ contract.file_number }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Montant de la caution :</strong> {{ formatCurrency(contract.caution_amount) }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Montant du loyer :</strong> {{ formatCurrency(contract.rent_amount) }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Commission :</strong> {{ formatCurrency(contract.commission_amount) }}
                            </p>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-700 mb-2">
                                Propriété
                            </h2>
                            <p class="text-gray-600">
                                <strong>Nom :</strong> {{ contract.property?.name || contract.property_name || "Propriété supprimée" }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Adresse :</strong> {{ contract.property?.address || contract.property_address || "N/A" }}
                            </p>
                            <div v-if="parsedPhotos.length > 0" class="grid grid-cols-2 gap-2 mt-4">
                                <img v-for="(photo, index) in parsedPhotos" :key="index"
                                    :src="`/storage/${photo}`" :alt="`Photo ${index + 1}`"
                                    class="w-full h-32 object-cover rounded-lg" />
                            </div>
                            <p class="text-gray-600 mt-4">
                                <strong>Bailleur :</strong>
                                {{ contract.property?.landlord?.name || contract.landlord_name || "Bailleur supprimé" }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-700 mb-2">
                                Locataire
                            </h2>
                            <p class="text-gray-600">
                                <strong>Nom :</strong>
                                {{ contract.tenant?.first_name || "Prénom supprimé" }}
                                {{ contract.tenant?.last_name || "Nom supprimé" }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Email :</strong> {{ contract.tenant?.email || "Email supprimé" }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Téléphone :</strong> {{ contract.tenant?.phone || "Téléphone supprimé" }}
                            </p>
                        </div>
                        <div>
        <h2 class="text-2xl font-semibold text-gray-700 mb-2">
            Informations Supplémentaires
        </h2>
        <p class="text-gray-600">
            <strong>Commission sur le loyer :</strong>
            {{ formatCurrency(rentCommission) }}
        </p>
        <p class="text-gray-600">
            <strong>Commission sur la caution :</strong>
            {{ formatCurrency(cautionCommission) }}
        </p>
        <p class="text-gray-600">
            <strong>Commission totale :</strong>
            {{ formatCurrency(totalCommission) }}
        </p>
    </div>
                    </div>

                    <div class="mt-6">
                        <Link :href="route('contracts.archives')" class="btn-primary">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const page = usePage();
const contract = ref(page.props.contract);
const rentCommission = ref(page.props.rentCommission);
const cautionCommission = ref(page.props.cautionCommission);
const totalCommission = ref(page.props.totalCommission);

const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    const date = new Date(dateString);
    return isNaN(date) ? "Invalid Date" : date.toLocaleDateString("fr-FR", options);
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

const parsedPhotos = computed(() => {
    return contract.value.property?.photos
        ? JSON.parse(contract.value.property.photos)
        : [];
});
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
}
</style>
