<template>
    <AppLayout title="Entreprises">
        <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0">
                    <i class="fas fa-building mr-2 text-indigo-600"></i>Liste des Entreprises
                </h1>
                <Link :href="route('companies.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <i class="fas fa-plus mr-2"></i> Ajouter une entreprise
                </Link>
            </div>

            <div v-if="!companies.length" class="text-center text-gray-500 py-10">
                <i class="fas fa-info-circle text-4xl mb-4 text-indigo-500"></i>
                <p>Aucune entreprise enregistrée</p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <Card
                    v-for="company in companies"
                    :key="company.id"
                    :title="company.name"
                    :email="company.email"
                    :editUrl="route('companies.edit', company.id)"
                    :viewUrl="route('companies.show', company.id)"
                    :isActive="company.is_active"
                    :canToggleStatus="true"
                    @delete="openDeleteModal(company)"
                    @toggleStatus="toggleCompanyStatus(company)"
                />
            </div>
        </div>

        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Confirmer la suppression
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Êtes-vous sûr de vouloir supprimer l'entreprise "{{ companyToDelete?.name }}" ?
                </p>
                <div class="mt-6 flex justify-end">
                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900" @click="closeDeleteModal">
                        Annuler
                    </button>
                    <button type="button"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        @click="confirmDelete">
                        Supprimer
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    companies: {
        type: Array,
        required: true,
    },
});

const companies = ref([...props.companies]);
const showDeleteModal = ref(false);
const companyToDelete = ref(null);

const toggleCompanyStatus = (company) => {
    router.patch(route('companies.toggle-status', company.id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            const updatedCompany = companies.value.find(c => c.id === company.id);
            if (updatedCompany) {
                updatedCompany.is_active = !updatedCompany.is_active;
            }
        }
    });
};

const openDeleteModal = (company) => {
    companyToDelete.value = company;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    companyToDelete.value = null;
};

const confirmDelete = () => {
    if (companyToDelete.value) {
        router.delete(route('companies.destroy', companyToDelete.value.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                companies.value = companies.value.filter(company => company.id !== companyToDelete.value.id);
                closeDeleteModal();
            }
        });
    }
};
</script>
