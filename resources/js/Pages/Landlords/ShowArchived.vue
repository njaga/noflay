<template>
    <AppLayout title="Détails du Bailleur Archivé">
        <div class="min-h-screen py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div
                    class="flex flex-col space-y-4 sm:flex-row sm:justify-between sm:items-center mb-8"
                >
                    <h2
                        class="text-2xl sm:text-3xl font-extrabold text-indigo-900"
                    >
                        Détails du Bailleur Archivé
                    </h2>
                    <div
                        class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2"
                    >
                        <button
                            @click="openRestoreModal"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:scale-105"
                        >
                            <i class="bi bi-arrow-counterclockwise mr-2"></i>
                            Restaurer
                        </button>
                        <button
                            @click="openDeleteModal"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 transform hover:scale-105"
                        >
                            <i class="bi bi-trash mr-2"></i>
                            Supprimer définitivement
                        </button>
                    </div>
                </div>

                <div
                    v-if="!landlord"
                    class="bg-white shadow-lg rounded-lg p-6 text-indigo-800"
                >
                    Aucun détail trouvé pour ce bailleur archivé.
                </div>
                <div v-else class="space-y-8">
                    <!-- Informations Personnelles -->
                    <div
                        class="bg-white shadow-lg rounded-lg overflow-hidden border border-indigo-200 transform transition-all duration-300 hover:shadow-xl"
                    >
                        <div
                            class="px-4 py-3 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-500 to-indigo-600 flex items-center"
                        >
                            <i class="bi bi-person-fill text-white mr-2"></i>
                            <h3 class="text-lg font-semibold text-white">
                                Informations Personnelles
                            </h3>
                        </div>
                        <div
                            class="p-4 sm:p-6 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3"
                        >
                            <InfoItem
                                icon="bi-person"
                                label="Nom"
                                :value="`${landlord.first_name} ${landlord.last_name}`"
                            />
                            <InfoItem
                                icon="bi-envelope"
                                label="Email"
                                :value="landlord.email"
                            />
                            <InfoItem
                                icon="bi-telephone"
                                label="Téléphone"
                                :value="landlord.phone"
                            />
                            <InfoItem
                                icon="bi-geo-alt"
                                label="Adresse"
                                :value="landlord.address"
                            />
                            <InfoItem
                                icon="bi-calendar-check"
                                label="Date d'inscription"
                                :value="formatDate(landlord.created_at)"
                            />
                            <InfoItem
                                icon="bi-calendar-x"
                                label="Date de suppression"
                                :value="formatDate(landlord.deleted_at)"
                            />
                        </div>
                    </div>

                    <!-- Propriétés Associées -->
                    <div
                        class="bg-white shadow-lg rounded-lg overflow-hidden border border-indigo-200 transform transition-all duration-300 hover:shadow-xl"
                    >
                        <div
                            class="px-4 py-3 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-400 to-indigo-500 flex items-center"
                        >
                            <i class="bi bi-house-fill text-white mr-2"></i>
                            <h3 class="text-lg font-semibold text-white">
                                Propriétés Associées
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div
                                v-if="properties.length === 0"
                                class="text-indigo-800 flex items-center justify-center p-4"
                            >
                                <i class="bi bi-house-slash text-4xl mr-2"></i>
                                <span>Aucune propriété associée.</span>
                            </div>
                            <div v-else class="overflow-x-auto">
                                <div
                                    class="inline-block min-w-full align-middle"
                                >
                                    <div
                                        class="overflow-hidden border-b border-gray-200 sm:rounded-lg"
                                    >
                                        <table
                                            class="min-w-full divide-y divide-indigo-200"
                                        >
                                            <thead class="bg-indigo-50">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider"
                                                    >
                                                        Nom
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider"
                                                    >
                                                        Adresse
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider"
                                                    >
                                                        Prix
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider"
                                                    >
                                                        Locataires Associés
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-indigo-100"
                                            >
                                                <tr
                                                    v-for="property in properties"
                                                    :key="property.id"
                                                    class="hover:bg-indigo-50 transition-colors duration-150"
                                                >
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm font-medium text-indigo-900"
                                                    >
                                                        {{ property.name }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700"
                                                    >
                                                        {{ property.address }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700"
                                                    >
                                                        {{
                                                            formatCurrency(
                                                                property.price
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700"
                                                    >
                                                        <ul>
                                                            <li
                                                                v-for="contract in property.contracts"
                                                                :key="
                                                                    contract.id
                                                                "
                                                            >
                                                                {{
                                                                    contract
                                                                        .tenant
                                                                        .first_name
                                                                }}
                                                                {{
                                                                    contract
                                                                        .tenant
                                                                        .last_name
                                                                }}
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions -->
                    <div
                        class="bg-white shadow-lg rounded-lg overflow-hidden border border-indigo-200 transform transition-all duration-300 hover:shadow-xl"
                    >
                        <div
                            class="px-4 py-3 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-300 to-indigo-400 flex items-center"
                        >
                            <i class="bi bi-cash-coin text-white mr-2"></i>
                            <h3 class="text-lg font-semibold text-white">
                                Transactions
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div
                                v-if="transactions.length === 0"
                                class="text-indigo-800 flex items-center justify-center p-4"
                            >
                                <i class="bi bi-cash-stack text-4xl mr-2"></i>
                                <span>Aucune transaction.</span>
                            </div>
                            <div v-else class="overflow-x-auto">
                                <div
                                    class="inline-block min-w-full align-middle"
                                >
                                    <div
                                        class="overflow-hidden border-b border-gray-200 sm:rounded-lg"
                                    >
                                        <table
                                            class="min-w-full divide-y divide-indigo-200"
                                        >
                                            <thead class="bg-indigo-50">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider"
                                                    >
                                                        Date
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider"
                                                    >
                                                        Montant
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider"
                                                    >
                                                        Type
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-indigo-100"
                                            >
                                                <tr
                                                    v-for="transaction in transactions"
                                                    :key="transaction.id"
                                                    class="hover:bg-indigo-50 transition-colors duration-150"
                                                >
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700"
                                                    >
                                                        {{
                                                            formatDate(
                                                                transaction.date
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700"
                                                    >
                                                        {{
                                                            formatCurrency(
                                                                transaction.amount
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-indigo-700"
                                                    >
                                                        {{ transaction.type }}
                                                    </td>
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
            :is-open="isModalOpen"
            :title="modalTitle"
            :message="modalMessage"
            :status="modalStatus"
            @close="closeModal"
            @confirm="confirmModal"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InfoItem from "@/Components/InfoItem.vue";
import ModalArchive from "@/Components/Landlords/ModalArchive.vue";

const props = defineProps({
    landlord: Object,
    properties: Array,
    transactions: Array,
});

const isModalOpen = ref(false);
const modalAction = ref("");
const modalStatus = ref("default");

const modalTitle = computed(() => {
    return modalAction.value === "restore"
        ? "Restaurer le Bailleur"
        : "Supprimer Définitivement le Bailleur";
});

const modalMessage = computed(() => {
    const action =
        modalAction.value === "restore"
            ? "restaurer"
            : "supprimer définitivement";
    return `Êtes-vous sûr de vouloir ${action} le bailleur ${props.landlord.first_name} ${props.landlord.last_name} ? Cette action n'est pas réversible.`;
});

const openRestoreModal = () => {
    modalAction.value = "restore";
    isModalOpen.value = true;
};

const openDeleteModal = () => {
    modalAction.value = "delete";
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    modalStatus.value = "default";
};

const confirmModal = () => {
    modalStatus.value = "loading";
    if (modalAction.value === "restore") {
        restoreLandlord();
    } else {
        deleteLandlord();
    }
};

const restoreLandlord = () => {
    router.post(
        route("landlords.restore", props.landlord.id),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                modalStatus.value = "success";
                setTimeout(() => {
                    closeModal();
                    window.location.reload();
                }, 1500);
            },
            onError: () => {
                modalStatus.value = "error";
            },
        }
    );
};

const deleteLandlord = () => {
    router.delete(route("landlords.forceDelete", props.landlord.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            modalStatus.value = "success";
            setTimeout(() => {
                closeModal();
                window.location.href = route("landlords.archives");
            }, 1500);
        },
        onError: () => {
            modalStatus.value = "error";
        },
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("fr-FR", {
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
</script>
