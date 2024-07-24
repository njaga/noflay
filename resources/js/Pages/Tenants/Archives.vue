<template>
    <AppLayout title="Archives des Locataires">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-3xl font-extrabold text-indigo-900 mb-6">Archives des Locataires</h2>
                <div v-if="tenants.length === 0" class="bg-white shadow-sm rounded-lg p-6 text-gray-600 text-center">
                    <i class="bi bi-file-earmark-x text-gray-400 text-4xl mb-4"></i>
                    <p>Aucun locataire archivé trouvé.</p>
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
                            <tr v-for="tenant in tenants" :key="tenant.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ tenant.first_name }} {{ tenant.last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ tenant.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ tenant.phone_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="openRestoreModal(tenant)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                    <button @click="viewTenant(tenant.id)" class="text-blue-600 hover:text-blue-900 mr-2">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button @click="openDeleteModal(tenant)" class="text-red-600 hover:text-red-900">
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
            title="Restaurer le locataire"
            :message="`Êtes-vous sûr de vouloir restaurer le locataire ${selectedTenant?.first_name} ${selectedTenant?.last_name} ?`"
            @close="closeRestoreModal"
            @confirm="confirmRestore"
        />

        <ModalArchive
            :is-open="showDeleteModal"
            title="Supprimer définitivement le locataire"
            :message="`Êtes-vous sûr de vouloir supprimer définitivement le locataire ${selectedTenant?.first_name} ${selectedTenant?.last_name} ? Cette action est irréversible.`"
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
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ModalArchive from '@/Components/Tenants/ModalArchive.vue';

const props = defineProps({
    tenants: Array,
    auth: Object,
});

const tenants = ref(props.tenants);
const showRestoreModal = ref(false);
const showDeleteModal = ref(false);
const selectedTenant = ref(null);
const showToast = ref(false);
const toastMessage = ref('');
const toastFading = ref(false);

const openRestoreModal = (tenant) => {
    selectedTenant.value = tenant;
    showRestoreModal.value = true;
};

const closeRestoreModal = () => {
    showRestoreModal.value = false;
    selectedTenant.value = null;
};

const openDeleteModal = (tenant) => {
    selectedTenant.value = tenant;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    selectedTenant.value = null;
};

const confirmRestore = () => {
    if (selectedTenant.value) {
        restoreTenant(selectedTenant.value.id);
    }
    closeRestoreModal();
};

const confirmDelete = () => {
    if (selectedTenant.value) {
        forceDeleteTenant(selectedTenant.value.id);
    }
    closeDeleteModal();
};

const restoreTenant = (id) => {
    router.post(route('tenants.restore', id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            tenants.value = tenants.value.filter(tenant => tenant.id !== id);
            showToast.value = true;
            toastMessage.value = `Le locataire a été restauré avec succès.`;
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
            toastMessage.value = `Échec lors de la restauration du locataire.`;
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

const viewTenant = (id) => {
    router.visit(route('tenants.showArchived', id), {
        method: 'get'
    });
};

const forceDeleteTenant = (id) => {
    router.post(route('tenants.forceDelete', id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            tenants.value = tenants.value.filter(tenant => tenant.id !== id);
            showToast.value = true;
            toastMessage.value = `Le locataire a été supprimé définitivement avec succès.`;
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
            toastMessage.value = `Échec lors de la suppression définitive du locataire.`;
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
