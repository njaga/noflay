<template>
    <AppLayout title="Contrats Archivés">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-between sm:items-center mb-8">
                    <h2 class="text-4xl font-extrabold text-indigo-900 tracking-tight">Contrats Archivés</h2>
                    <div class="flex items-center space-x-4 bg-white p-2 rounded-lg shadow-md">
                        <button @click="viewMode = 'grid'" :class="['btn-view', { 'btn-view-active': viewMode === 'grid' }]">
                            <i class="fas fa-th-large mr-2"></i> Grille
                        </button>
                        <button @click="viewMode = 'list'" :class="['btn-view', { 'btn-view-active': viewMode === 'list' }]">
                            <i class="fas fa-list mr-2"></i> Liste
                        </button>
                    </div>
                </div>

                <TransitionGroup
                    name="list"
                    tag="div"
                    :class="[viewMode === 'grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6' : 'space-y-4']"
                >
                    <div v-if="!contracts.data.length" key="empty" class="col-span-full bg-white rounded-lg shadow-lg p-8 text-center">
                        <i class="fas fa-file-contract text-7xl text-indigo-300 mb-4"></i>
                        <p class="text-2xl text-gray-700 font-semibold">Aucun contrat archivé</p>
                    </div>

                    <div v-else v-for="contract in contracts.data" :key="contract.id"
                        :class="[
                            'bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300',
                            viewMode === 'grid' ? 'transform hover:-translate-y-2' : 'flex items-center'
                        ]"
                    >
                        <div :class="[viewMode === 'grid' ? 'p-6' : 'p-4 flex-grow']">
                            <div :class="[viewMode === 'list' ? 'flex items-center justify-between' : '']">
                                <div>
                                    <h3 class="text-2xl font-bold text-indigo-900 mb-3">Contrat #{{ contract.id }}</h3>
                                    <p class="text-sm text-gray-600 mb-2 flex items-center">
                                        <i class="fas fa-user mr-2 text-indigo-500"></i>
                                        {{ contract.tenant?.first_name || 'Locataire inconnu' }} {{ contract.tenant?.last_name || '' }}
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2 flex items-center">
                                        <i class="fas fa-home mr-2 text-indigo-500"></i>
                                        {{ contract.property?.name || 'Propriété supprimée' }}
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2 flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                                        Du {{ formatDate(contract.start_date) }} au {{ formatDate(contract.end_date) }}
                                    </p>
                                </div>
                                <div :class="[
                                    viewMode === 'grid' ? 'mt-6 flex justify-center space-x-4' :
                                    'flex space-x-2 md:space-x-4'
                                ]">
                                    <button @click="openRestoreModal(contract)"
                                        :class="[
                                            'btn-icon',
                                            viewMode === 'list' && !isMobile ? 'btn-action bg-emerald-500 hover:bg-emerald-600' : ''
                                        ]"
                                        :title="'Restaurer'"
                                    >
                                        <i class="fas fa-undo" :class="{'mr-2': viewMode === 'list' && !isMobile}"></i>
                                        <span v-if="viewMode === 'list' && !isMobile">Restaurer</span>
                                    </button>
                                    <Link v-if="contract.id" :href="route('contracts.showArchived', contract.id)"
                                        :class="[
                                            'btn-icon',
                                            viewMode === 'list' && !isMobile ? 'btn-action bg-blue-500 hover:bg-blue-600' : ''
                                        ]"
                                        :title="'Voir'"
                                    >
                                        <i class="fas fa-eye" :class="{'mr-2': viewMode === 'list' && !isMobile}"></i>
                                        <span v-if="viewMode === 'list' && !isMobile">Voir</span>
                                    </Link>
                                    <button @click="openDeleteModal(contract)"
                                        :class="[
                                            'btn-icon',
                                            viewMode === 'list' && !isMobile ? 'btn-action bg-red-500 hover:bg-red-600' : ''
                                        ]"
                                        :title="'Supprimer'"
                                    >
                                        <i class="fas fa-trash" :class="{'mr-2': viewMode === 'list' && !isMobile}"></i>
                                        <span v-if="viewMode === 'list' && !isMobile">Supprimer</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>

                <!-- Pagination -->
                <div v-if="contracts.data.length > 0" class="mt-12 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, index) in contracts.links" :key="index">
                            <Link
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    {
                                        'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': link.active,
                                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active,
                                        'rounded-l-md': index === 0,
                                        'rounded-r-md': index === contracts.links.length - 1
                                    }
                                ]"
                            />
                        </template>
                    </nav>
                </div>

                <!-- Modal de confirmation de suppression -->
                <ModalArchive v-model:open="showDeleteModal" title="Supprimer Définitivement le Contrat"
                    :message="`Êtes-vous sûr de vouloir supprimer définitivement le contrat #${contractToDelete?.id} ? Cette action est irréversible.`"
                    status="error" @close="closeDeleteModal" @confirm="confirmDelete" />

                <!-- Modal de confirmation de restauration -->
                <ModalArchive v-model:open="showRestoreModal" title="Restaurer le Contrat"
                    :message="`Êtes-vous sûr de vouloir restaurer le contrat #${contractToRestore?.id} ?`"
                    status="warning" @close="closeRestoreModal" @confirm="confirmRestore" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ModalArchive from '@/Components/Properties/ModalArchive.vue';

const page = usePage();
const contracts = ref(page.props.contracts);
const showDeleteModal = ref(false);
const contractToDelete = ref(null);
const showRestoreModal = ref(false);
const contractToRestore = ref(null);
const viewMode = ref('grid');
const showModal = ref(false);
const modalMessage = ref('');
const isMobile = ref(false);

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return isNaN(date) ? 'Invalid Date' : date.toLocaleDateString('fr-FR', options);
};

const openRestoreModal = (contract) => {
    contractToRestore.value = contract;
    showRestoreModal.value = true;
};

const closeRestoreModal = () => {
    showRestoreModal.value = false;
    contractToRestore.value = null;
};

const confirmRestore = () => {
    if (contractToRestore.value) {
        router.post(route('contracts.restore', contractToRestore.value.id), {}, {
            onSuccess: (response) => {
                contracts.value.data = contracts.value.data.filter(c => c.id !== contractToRestore.value.id);
                closeRestoreModal();
                openSuccessModal(response.data.message);
            },
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const openDeleteModal = (contract) => {
    contractToDelete.value = contract;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    contractToDelete.value = null;
};

const confirmDelete = () => {
    if (contractToDelete.value) {
        router.post(route('contracts.forceDelete', contractToDelete.value.id), {}, {
            onSuccess: (response) => {
                contracts.value.data = contracts.value.data.filter(c => c.id !== contractToDelete.value.id);
                closeDeleteModal();
                openSuccessModal(response.data.message);
            },
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const openSuccessModal = (message) => {
    showModal.value = true;
    modalMessage.value = message;
};

const closeModal = () => {
    showModal.value = false;
    modalMessage.value = '';
};

const checkMobile = () => {
    isMobile.value = window.innerWidth < 768; // Considère comme mobile si la largeur est inférieure à 768px
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);

    // Animation d'entrée pour les éléments de la liste
    const listItems = document.querySelectorAll('.list-enter-active');
    listItems.forEach((item, index) => {
        item.style.transitionDelay = `${index * 50}ms`;
    });
});

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile);
});
</script>

<style scoped>
.btn-view {
    @apply px-4 py-2 rounded-full text-sm font-medium transition-colors duration-150;
}
.btn-view-active {
    @apply bg-indigo-600 text-white;
}
.btn-view:not(.btn-view-active) {
    @apply bg-white text-gray-700 hover:bg-gray-100;
}
.btn-action {
    @apply px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors duration-150 shadow-md hover:shadow-lg;
}

.btn-icon {
    @apply p-2 rounded-full text-white transition-colors duration-150 shadow-md hover:shadow-lg;
}

.btn-icon:not(.btn-action) {
    @apply bg-gray-200 text-gray-700 hover:bg-gray-300;
}

.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(30px);
}

@media (max-width: 767px) {
    .btn-action {
        @apply p-2 rounded-full;
    }
    .btn-action span {
        @apply hidden;
    }
}
</style>
