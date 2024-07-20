<template>
    <AppLayout :title="'Détails de ' + company.name">
        <div class="min-h-screen py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-white bg-opacity-90 backdrop-filter backdrop-blur-lg overflow-hidden shadow-2xl rounded-3xl">
                    <div class="p-8">
                        <!-- En-tête avec le nom de l'entreprise et les boutons d'action -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-12">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center">
                                    <i class="fas fa-building text-3xl text-white"></i>
                                </div>
                                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                                    {{ company.name }}
                                </h1>
                            </div>
                            <div class="flex space-x-4 mt-4 sm:mt-0">
                                <Link :href="route('companies.edit', company.id)" class="btn-primary">
                                <i class="fas fa-edit mr-2"></i>
                                Modifier
                                </Link>
                                <button @click="confirmDelete" class="btn-danger">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>

                        <!-- Informations de l'entreprise -->
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-50 p-8 rounded-2xl mb-12 shadow-inner">
                            <h2 class="text-2xl font-semibold text-indigo-800 mb-6 flex items-center">
                                <i class="fas fa-info-circle mr-3 text-indigo-600"></i> Informations de l'entreprise
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div v-for="(value, key) in companyInfo" :key="key" class="flex items-start">
                                    <div
                                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                        <i :class="['fas', getIcon(key), 'text-indigo-600 text-xl']"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-indigo-600 mb-1">{{ getLabel(key) }}</p>
                                        <p v-if="key === 'website'" class="text-lg text-gray-800">
                                            <a v-if="value" :href="value" target="_blank"
                                                class="text-indigo-600 hover:text-indigo-800 underline">
                                                {{ value }}
                                            </a>
                                            <span v-else>Non spécifié</span>
                                        </p>
                                        <p v-else-if="key === 'is_active'" class="text-lg">
                                            <span
                                                :class="value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                                class="px-3 py-1 inline-flex text-sm font-semibold rounded-full">
                                                {{ value ? "Actif" : "Inactif" }}
                                            </span>
                                        </p>
                                        <p v-else class="text-lg text-gray-800">{{ value || "Non spécifié" }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sections pour les utilisateurs, bailleurs, locataires et propriétés -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <AssociatedSection title="Utilisateurs associés" icon="fas fa-users" :items="company.users">
                                <template #item="{ item: user }">
                                    <div class="flex items-center space-x-4">
                                        <img class="h-12 w-12 rounded-full object-cover" :src="user.profile_photo_url"
                                            :alt="user.name" />
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ user.name }}</p>
                                            <p class="text-sm text-gray-600">{{ user.email }}</p>
                                            <p class="text-xs text-indigo-600 mt-1">
                                                {{ user.roles.map(role => role.name).join(', ') }}
                                            </p>
                                        </div>
                                    </div>
                                </template>
                            </AssociatedSection>

                            <AssociatedSection title="Bailleurs associés" icon="fas fa-user-tie"
                                :items="company.landlords">
                                <template #item="{ item: landlord }">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ landlord.first_name }} {{
                                            landlord.last_name }}</p>
                                        <p class="text-sm text-gray-600">{{ landlord.email }}</p>
                                        <p class="text-sm text-gray-600">{{ landlord.phone }}</p>
                                    </div>
                                </template>
                            </AssociatedSection>

                            <AssociatedSection title="Locataires associés" icon="fas fa-home" :items="company.tenants">
                                <template #item="{ item: tenant }">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ tenant.first_name }} {{
                                            tenant.last_name }}</p>
                                        <p class="text-sm text-gray-600">{{ tenant.email }}</p>
                                        <p class="text-sm text-gray-600">{{ tenant.phone_number }}</p>
                                    </div>
                                </template>
                            </AssociatedSection>

                            <AssociatedSection title="Propriétés associées" icon="fas fa-building"
                                :items="company.properties">
                                <template #item="{ item: property }">
                                    <div class="flex space-x-4">
                                        <img :src="getPropertyImage(property)" alt="Property Image"
                                            class="w-24 h-24 object-cover rounded-lg" />
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ property.name }}</p>
                                            <p class="text-sm text-gray-600">{{ property.address }}</p>
                                            <p class="text-sm text-indigo-600">{{ property.property_type }}</p>
                                        </div>
                                    </div>
                                </template>
                            </AssociatedSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation de suppression -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                    Confirmer la suppression
                </h2>
                <p class="mb-6 text-gray-600">
                    Êtes-vous sûr de vouloir supprimer cette entreprise ? Cette action est irréversible.
                </p>
                <div class="flex justify-end space-x-4">
                    <button @click="closeDeleteModal" class="btn-secondary">
                        Annuler
                    </button>
                    <button @click="deleteCompany" class="btn-danger">
                        Supprimer
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script>
import { defineComponent, ref, computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import AssociatedSection from "@/Components/AssociatedSection.vue";

export default defineComponent({
    components: {
        AppLayout,
        Link,
        Modal,
        AssociatedSection
    },
    props: {
        company: Object,
    },
    setup(props) {
        const showDeleteModal = ref(false);

        const companyInfo = computed(() => ({
            email: props.company.email,
            phone: props.company.phone,
            website: props.company.website,
            is_active: props.company.is_active,
            address: props.company.address
        }));

        const getIcon = (key) => {
            const icons = {
                email: 'fa-envelope',
                phone: 'fa-phone',
                website: 'fa-globe',
                is_active: 'fa-toggle-on',
                address: 'fa-map-marker-alt'
            };
            return icons[key] || 'fa-info-circle';
        };

        const getLabel = (key) => {
            const labels = {
                email: 'Email',
                phone: 'Téléphone',
                website: 'Site web',
                is_active: 'Statut',
                address: 'Adresse'
            };
            return labels[key] || key.replace('_', ' ').charAt(0).toUpperCase() + key.replace('_', ' ').slice(1);
        };

        const confirmDelete = () => {
            showDeleteModal.value = true;
        };

        const closeDeleteModal = () => {
            showDeleteModal.value = false;
        };

        const deleteCompany = () => {
            router.delete(route("companies.destroy", props.company.id), {
                preserveState: false,
                preserveScroll: false,
                onSuccess: () => {
                    router.visit(route("companies.index"));
                },
            });
        };

        const getPropertyImage = (property) => {
            if (property.photos && property.photos.length) {
                try {
                    const parsedPhotos = JSON.parse(property.photos);
                    if (parsedPhotos.length > 0) {
                        const photoPath = parsedPhotos[0].replace(/^public\//, '');
                        return `/storage/${photoPath}`;
                    }
                } catch (error) {
                    console.error("Error parsing property photos:", error);
                }
            }
            return 'https://via.placeholder.com/150'; // Image par défaut
        };

        return {
            showDeleteModal,
            confirmDelete,
            closeDeleteModal,
            deleteCompany,
            companyInfo,
            getIcon,
            getLabel,
            getPropertyImage
        };
    },
});
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-600 hover:to-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
}

.btn-danger {
    @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-red-600 hover:to-pink-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition ease-in-out duration-150;
}

.btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150;
}
</style>
