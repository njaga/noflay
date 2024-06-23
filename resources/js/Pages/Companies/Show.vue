<template>
    <AppLayout :title="'Détails de ' + company.name">
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:p-8">
                        <!-- Bouton de retour -->
                        <Link :href="route('companies.index')"
                            class="inline-flex items-center mb-6 text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                        </Link>

                        <!-- En-tête avec le nom de l'entreprise et les boutons d'action -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0 flex items-center">
                                <i class="fas fa-building mr-3 text-indigo-600"></i>
                                {{ company.name }}
                            </h1>
                            <div class="flex space-x-2">
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
                        <div class="bg-indigo-50 p-6 rounded-lg mb-8 shadow-sm">
                            <h2 class="text-xl font-semibold text-indigo-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Informations de l'entreprise
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div v-for="(value, key) in companyInfo" :key="key" class="flex items-start">
                                    <i :class="['fas', getIcon(key), 'mr-3', 'text-indigo-500', 'mt-1']"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 mb-1">{{ getLabel(key) }}</p>
                                        <p v-if="key === 'website'" class="text-base text-gray-900">
                                            <a v-if="value" :href="value" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                                {{ value }}
                                            </a>
                                            <span v-else>Non spécifié</span>
                                        </p>
                                        <p v-else-if="key === 'is_active'" class="text-base">
                                            <span :class="value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ value ? "Actif" : "Inactif" }}
                                            </span>
                                        </p>
                                        <p v-else class="text-base text-gray-900">{{ value || "Non spécifié" }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Utilisateurs associés -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-users mr-2 text-indigo-600"></i> Utilisateurs associés
                            </h2>
                            <div v-if="company.users && company.users.length > 0"
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="user in company.users" :key="user.id"
                                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full" :src="user.profile_photo_url" :alt="user.name" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ user.name }}</p>
                                            <p class="text-sm text-gray-500 truncate">{{ user.email }}</p>
                                            <p class="mt-1 text-sm text-gray-500">{{ getRoleLabel(user.role) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-gray-600 bg-gray-100 p-4 rounded-lg">
                                Aucun utilisateur associé à cette entreprise.
                            </p>
                        </div>

                        <!-- Bailleurs associés -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-tie mr-2 text-indigo-600"></i> Bailleurs associés
                            </h2>
                            <div v-if="company.landlords && company.landlords.length > 0"
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="landlord in company.landlords" :key="landlord.id"
                                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                    <p class="font-medium text-gray-900">{{ landlord.prenom }} {{ landlord.nom }}</p>
                                    <p class="text-sm text-gray-500">{{ landlord.email }}</p>
                                    <p class="text-sm text-gray-500">{{ landlord.telephone }}</p>
                                </div>
                            </div>
                            <p v-else class="text-gray-600 bg-gray-100 p-4 rounded-lg">
                                Aucun bailleur associé à cette entreprise.
                            </p>
                        </div>

                        <!-- Locataires associés -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-home mr-2 text-indigo-600"></i> Locataires associés
                            </h2>
                            <div v-if="company.tenants && company.tenants.length > 0"
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="tenant in company.tenants" :key="tenant.id"
                                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                    <p class="font-medium text-gray-900">{{ tenant.prenom }} {{ tenant.nom }}</p>
                                    <p class="text-sm text-gray-500">{{ tenant.email }}</p>
                                    <p class="text-sm text-gray-500">{{ tenant.telephone }}</p>
                                </div>
                            </div>
                            <p v-else class="text-gray-600 bg-gray-100 p-4 rounded-lg">
                                Aucun locataire associé à cette entreprise.
                            </p>
                        </div>

                        <!-- Propriétés associées -->
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-building mr-2 text-indigo-600"></i> Propriétés associées
                            </h2>
                            <div v-if="company.properties && company.properties.length > 0"
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="property in company.properties" :key="property.id"
                                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                    <p class="font-medium text-gray-900">{{ property.name }}</p>
                                    <p class="text-sm text-gray-500">{{ property.address }}</p>
                                    <p class="text-sm text-gray-500">{{ property.type }}</p>
                                </div>
                            </div>
                            <p v-else class="text-gray-600 bg-gray-100 p-4 rounded-lg">
                                Aucune propriété associée à cette entreprise.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation de suppression -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    Confirmer la suppression
                </h2>
                <p class="mb-4 text-sm text-gray-600">
                    Êtes-vous sûr de vouloir supprimer cette entreprise ? Cette action est irréversible.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
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

export default defineComponent({
    components: {
        AppLayout,
        Link,
        Modal
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
            return labels[key] || key.replace('_', ' ').capitalize();
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

        const getRoleLabel = (role) => {
            const labels = {
                super_admin: 'Super Admin',
                user_entreprise: 'Utilisateur',
                landlord: 'Bailleur',
                tenant: 'Locataire',
                admin_entreprise: 'Admin'
            };
            return labels[role] || 'N/A';
        };

        return {
            showDeleteModal,
            confirmDelete,
            closeDeleteModal,
            deleteCompany,
            companyInfo,
            getIcon,
            getLabel,
            getRoleLabel
        };
    },
});
</script>

<style scoped>
.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #4f46e5;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.75rem;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.btn-primary:hover {
    background-color: #4338ca;
}

.btn-danger {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #dc2626;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.75rem;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.btn-danger:hover {
    background-color: #b91c1c;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: white;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.75rem;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.btn-secondary:hover {
    background-color: #f3f4f6;
}
</style>
