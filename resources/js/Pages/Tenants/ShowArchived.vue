<template>
    <AppLayout title="Détails du Locataire Archivé">
        <div class="min-h-screen py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-between sm:items-center mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-indigo-900">Détails du Locataire Archivé</h2>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <button @click="openRestoreModal" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:scale-105">
                            <i class="bi bi-arrow-counterclockwise mr-2"></i>
                            Restaurer
                        </button>
                        <button @click="openDeleteModal" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 transform hover:scale-105">
                            <i class="bi bi-trash mr-2"></i>
                            Supprimer définitivement
                        </button>
                    </div>
                </div>

                <div v-if="!tenant" class="bg-white shadow-lg rounded-lg p-6 text-indigo-800">
                    Aucun détail trouvé pour ce locataire archivé.
                </div>
                <div v-else class="space-y-8">
                    <!-- Informations Personnelles -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-indigo-200 transform transition-all duration-300 hover:shadow-xl">
                        <div class="px-4 py-3 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-500 to-indigo-600 flex items-center">
                            <i class="bi bi-person-fill text-white mr-2"></i>
                            <h3 class="text-lg font-semibold text-white">Informations Personnelles</h3>
                        </div>
                        <div class="p-4 sm:p-6 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                            <InfoItem icon="bi-person" label="Nom" :value="`${tenant.first_name} ${tenant.last_name}`" />
                            <InfoItem icon="bi-envelope" label="Email" :value="tenant.email" />
                            <InfoItem icon="bi-telephone" label="Téléphone" :value="tenant.phone_number" />
                            <InfoItem icon="bi-geo-alt" label="Adresse" :value="tenant.address" />
                            <InfoItem icon="bi-calendar-check" label="Date d'inscription" :value="formatDate(tenant.created_at)" />
                            <InfoItem icon="bi-calendar-x" label="Date de suppression" :value="formatDate(tenant.deleted_at)" />
                        </div>
                    </div>

                    <!-- Propriétés Associées -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-indigo-200 transform transition-all duration-300 hover:shadow-xl">
                        <div class="px-4 py-3 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-400 to-indigo-500 flex items-center">
                            <i class="bi bi-house-fill text-white mr-2"></i>
                            <h3 class="text-lg font-semibold text-white">Propriétés Associées</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div v-if="properties.length === 0" class="text-indigo-800 flex items-center justify-center p-4">
                                <i class="bi bi-house-slash text-4xl mr-2"></i>
                                <span>Aucune propriété associée.</span>
                            </div>
                            <div v-else class="overflow-x-auto">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-indigo-200">
                                            <thead class="bg-indigo-50">
                                                <tr>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">Nom</th>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">Adresse</th>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">Bailleur</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-indigo-100">
                                                <tr v-for="property in properties" :key="property.id" class="hover:bg-indigo-50 transition-colors duration-150">
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-indigo-900">{{ property.name }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700">{{ property.address }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700">{{ property.landlord.first_name }} {{ property.landlord.last_name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paiements Précédents -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-indigo-200 transform transition-all duration-300 hover:shadow-xl">
                        <div class="px-4 py-3 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-300 to-indigo-400 flex items-center">
                            <i class="bi bi-cash-coin text-white mr-2"></i>
                            <h3 class="text-lg font-semibold text-white">Paiements Précédents</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div v-if="payments.length === 0" class="text-indigo-800 flex items-center justify-center p-4">
                                <i class="bi bi-cash-stack text-4xl mr-2"></i>
                                <span>Aucun paiement précédent.</span>
                            </div>
                            <div v-else class="overflow-x-auto">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-indigo-200">
                                            <thead class="bg-indigo-50">
                                                <tr>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">Date</th>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">Montant</th>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">Propriété</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-indigo-100">
                                                <tr v-for="payment in payments" :key="payment.id" class="hover:bg-indigo-50 transition-colors duration-150">
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-indigo-900">{{ formatDate(payment.payment_date) }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700">{{ formatCurrency(payment.amount) }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700">{{ payment.contract.property.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <ModalArchive
            :is-open="isRestoreModalOpen"
            :title="'Restaurer le locataire'"
            :message="`Êtes-vous sûr de vouloir restaurer le locataire ${tenant?.first_name} ${tenant?.last_name} ?`"
            @close="closeRestoreModal"
            @confirm="restoreTenant"
        />

        <ModalArchive
            :is-open="isDeleteModalOpen"
            :title="'Supprimer définitivement le locataire'"
            :message="`Êtes-vous sûr de vouloir supprimer définitivement le locataire ${tenant?.first_name} ${tenant?.last_name} ?`"
            @close="closeDeleteModal"
            @confirm="deleteTenant"
        />

        <ModalArchive
            :is-open="isNotificationModalOpen"
            :title="notificationTitle"
            :message="notificationMessage"
            @close="closeNotificationModal"
            @confirm="closeNotificationModal"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InfoItem from '@/Components/InfoItem.vue';
import ModalArchive from '@/Components/Tenants/ModalArchive.vue';

const page = usePage();

const tenant = computed(() => page.props.tenant || null);
const properties = computed(() => page.props.properties || []);
const payments = computed(() => page.props.payments || []);

const isRestoreModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isNotificationModalOpen = ref(false);
const notificationTitle = ref('');
const notificationMessage = ref('');

const openRestoreModal = () => isRestoreModalOpen.value = true;
const closeRestoreModal = () => isRestoreModalOpen.value = false;
const openDeleteModal = () => isDeleteModalOpen.value = true;
const closeDeleteModal = () => isDeleteModalOpen.value = false;

const openNotificationModal = (title, message) => {
    notificationTitle.value = title;
    notificationMessage.value = message;
    isNotificationModalOpen.value = true;
};
const closeNotificationModal = () => isNotificationModalOpen.value = false;

const restoreTenant = () => {
    router.post(route('tenants.restore', tenant.value.id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeRestoreModal();
            openNotificationModal('Succès', `Le locataire ${tenant.value.first_name} ${tenant.value.last_name} a été restauré avec succès.`);
        },
        onError: () => {
            closeRestoreModal();
            openNotificationModal('Erreur', `Échec lors de la restauration du locataire ${tenant.value.first_name} ${tenant.value.last_name}.`);
        }
    });
};

const deleteTenant = () => {
    router.delete(route('tenants.destroyPermanently', tenant.value.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            openNotificationModal('Succès', `Le locataire ${tenant.value.first_name} ${tenant.value.last_name} a été supprimé définitivement avec succès.`);
        },
        onError: () => {
            closeDeleteModal();
            openNotificationModal('Erreur', `Échec lors de la suppression définitive du locataire ${tenant.value.first_name} ${tenant.value.last_name}.`);
        }
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(amount);
};
</script>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@media (max-width: 640px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }

    table {
        display: block;
        width: 100%;
    }

    thead {
        display: none;
    }

    tbody {
        display: block;
    }

    tr {
        display: block;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
    }

    td {
        display: block;
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 1rem;
        font-weight: bold;
        text-align: left;
    }
}
</style>
