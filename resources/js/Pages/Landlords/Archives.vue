<template>
    <AppLayout title="Archives des Bailleurs">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-3xl font-extrabold text-indigo-900 mb-6">Archives des Bailleurs</h2>
                <div v-if="landlords.length === 0" class="bg-white shadow-sm rounded-lg p-6 text-gray-600 text-center">
                    <i class="bi bi-file-earmark-x text-gray-400 text-4xl mb-4"></i>
                    <p>Aucun bailleur archivé trouvé.</p>
                </div>
                <div v-else class="overflow-x-auto bg-white shadow-md rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="landlord in landlords" :key="landlord.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ landlord.first_name }} {{ landlord.last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ landlord.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ landlord.phone_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="openRestoreModal(landlord)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                    <button @click="viewLandlord(landlord.id)" class="text-blue-600 hover:text-blue-900 mr-2">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button @click="openDeleteModal(landlord)" class="text-red-600 hover:text-red-900">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <ModalArchive
            :is-open="showRestoreModal"
            title="Restaurer le bailleur"
            :message="`Êtes-vous sûr de vouloir restaurer le bailleur ${selectedLandlord?.first_name} ${selectedLandlord?.last_name} ?`"
            @close="closeRestoreModal"
            @confirm="confirmRestore"
        />

        <ModalArchive
            :is-open="showDeleteModal"
            title="Supprimer définitivement le bailleur"
            :message="`Êtes-vous sûr de vouloir supprimer définitivement le bailleur ${selectedLandlord?.first_name} ${selectedLandlord?.last_name} ? Cette action est irréversible.`"
            @close="closeDeleteModal"
            @confirm="confirmDelete"
        />

        <!-- Toast de notification -->
        <div v-if="showToast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg transition-opacity duration-500" :class="{ 'opacity-0': toastFading }">
            {{ toastMessage }}
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ModalArchive from '@/Components/Landlords/ModalArchive.vue';

const props = defineProps({
    landlords: Array,
    auth: Object,
});

const landlords = ref(props.landlords);
const showRestoreModal = ref(false);
const showDeleteModal = ref(false);
const selectedLandlord = ref(null);
const showToast = ref(false);
const toastMessage = ref('');
const toastFading = ref(false);

const openRestoreModal = (landlord) => {
    selectedLandlord.value = landlord;
    showRestoreModal.value = true;
};

const closeRestoreModal = () => {
    showRestoreModal.value = false;
    selectedLandlord.value = null;
};

const openDeleteModal = (landlord) => {
    selectedLandlord.value = landlord;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    selectedLandlord.value = null;
};

const confirmRestore = () => {
    if (selectedLandlord.value) {
        restoreLandlord(selectedLandlord.value.id);
    }
    closeRestoreModal();
};

const confirmDelete = () => {
    if (selectedLandlord.value) {
        forceDeleteLandlord(selectedLandlord.value.id);
    }
    closeDeleteModal();
};

const restoreLandlord = (id) => {
    router.post(route('landlords.restore', id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            landlords.value = landlords.value.filter(landlord => landlord.id !== id);
            showToast.value = true;
            toastMessage.value = `Le bailleur a été restauré avec succès.`;
            setTimeout(() => {
                toastFading.value = true;
                setTimeout(() => {
                    showToast.value = false;
                    toastFading.value = false;
                }, 500);
            }, 3000);
        },
        onError: () => {
            showToast.value = true;
            toastMessage.value = `Échec lors de la restauration du bailleur.`;
            setTimeout(() => {
                toastFading.value = true;
                setTimeout(() => {
                    showToast.value = false;
                    toastFading.value = false;
                }, 500);
            }, 3000);
        }
    });
};

const viewLandlord = (id) => {
    router.visit(route('landlords.showArchived', id), {
        method: 'get'
    });
};

const forceDeleteLandlord = (id) => {
    router.post(route('landlords.forceDelete', id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            landlords.value = landlords.value.filter(landlord => landlord.id !== id);
            showToast.value = true;
            toastMessage.value = `Le bailleur a été supprimé définitivement avec succès.`;
            setTimeout(() => {
                toastFading.value = true;
                setTimeout(() => {
                    showToast.value = false;
                    toastFading.value = false;
                }, 500);
            }, 3000);
        },
        onError: () => {
            showToast.value = true;
            toastMessage.value = `Échec lors de la suppression définitive du bailleur.`;
            setTimeout(() => {
                toastFading.value = true;
                setTimeout(() => {
                    showToast.value = false;
                    toastFading.value = false;
                }, 500);
            }, 3000);
        }
    });
};
</script>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css");
</style>
