<template>
    <AppLayout :title="'Détails de ' + landlord.prenom + ' ' + landlord.nom">
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:p-8">
                        <!-- Bouton de retour -->
                        <Link :href="route('landlords.index')"
                            class="inline-flex items-center mb-6 text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                        </Link>

                        <!-- En-tête avec le nom du bailleur et les boutons d'action -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0 flex items-center">
                                <i class="fas fa-user-tie mr-3 text-indigo-600"></i>
                                {{ landlord.prenom }} {{ landlord.nom }}
                            </h1>
                            <div class="flex space-x-2">
                                <Link :href="route('landlords.edit', landlord.id)" class="btn-primary">
                                <i class="fas fa-edit mr-2"></i>
                                Modifier
                                </Link>
                                <button @click="confirmDelete" class="btn-danger">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>

                        <!-- Informations du bailleur -->
                        <div class="bg-indigo-50 p-6 rounded-lg mb-8 shadow-sm">
                            <h2 class="text-xl font-semibold text-indigo-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Informations personnelles
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div v-for="(value, key) in landlordInfo" :key="key" class="flex items-start">
                                    <i :class="['fas', getIcon(key), 'mr-3', 'text-indigo-500', 'mt-1']"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 mb-1">{{ getLabel(key) }}</p>
                                        <p class="text-base text-gray-900">{{ value }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Propriétés associées -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-building mr-2 text-indigo-600"></i> Propriétés associées
                            </h2>
                            <div v-if="landlord.properties && landlord.properties.length > 0"
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="property in landlord.properties" :key="property.id"
                                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                    <p class="font-medium text-gray-900">{{ property.name }}</p>
                                    <p class="text-sm text-gray-500">{{ property.address }}</p>
                                </div>
                            </div>
                            <p v-else class="text-gray-600 bg-gray-100 p-4 rounded-lg">
                                Aucune propriété associée à ce bailleur.
                            </p>
                        </div>

                        <!-- Locataires associés -->
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-users mr-2 text-indigo-600"></i> Locataires associés
                            </h2>
                            <div v-if="landlord.tenants && landlord.tenants.length > 0"
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="tenant in landlord.tenants" :key="tenant.id"
                                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                    <p class="font-medium text-gray-900">{{ tenant.nom }} {{ tenant.prenom }}</p>
                                    <p class="text-sm text-gray-500">{{ tenant.email }}</p>
                                </div>
                            </div>
                            <p v-else class="text-gray-600 bg-gray-100 p-4 rounded-lg">
                                Aucun locataire associé à ce bailleur.
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
                    Êtes-vous sûr de vouloir supprimer ce bailleur ? Cette action est irréversible.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="closeDeleteModal" class="btn-secondary">
                        Annuler
                    </button>
                    <button @click="deleteLandlord" class="btn-danger">
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
        landlord: Object,
    },
    setup(props) {
        const showDeleteModal = ref(false);

        const landlordInfo = computed(() => ({
            email: props.landlord.email,
            telephone: props.landlord.telephone,
            numero_cni_passport: props.landlord.numero_cni_passport,
            date_expiration: formatDate(props.landlord.date_expiration),
            adresse: props.landlord.adresse,
            pourcentage_agence: props.landlord.pourcentage_agence + '%'
        }));

        const getIcon = (key) => {
            const icons = {
                email: 'fa-envelope',
                telephone: 'fa-phone',
                numero_cni_passport: 'fa-id-card',
                date_expiration: 'fa-calendar',
                adresse: 'fa-map-marker-alt',
                pourcentage_agence: 'fa-percentage'
            };
            return icons[key] || 'fa-info-circle';
        };

        const getLabel = (key) => {
            const labels = {
                email: 'Email',
                telephone: 'Téléphone',
                numero_cni_passport: 'Numéro CNI ou passport',
                date_expiration: 'Date d\'expiration',
                adresse: 'Adresse',
                pourcentage_agence: 'Pourcentage de l\'agence'
            };
            return labels[key] || key.replace('_', ' ').capitalize();
        };

        const confirmDelete = () => {
            showDeleteModal.value = true;
        };

        const closeDeleteModal = () => {
            showDeleteModal.value = false;
        };

        const deleteLandlord = () => {
            router.delete(route("landlords.destroy", props.landlord.id), {
                preserveState: false,
                preserveScroll: false,
                onSuccess: () => {
                    router.visit(route("landlords.index"));
                },
            });
        };

        const formatDate = (dateString) => {
            return new Date(dateString).toLocaleDateString();
        };

        return {
            showDeleteModal,
            confirmDelete,
            closeDeleteModal,
            deleteLandlord,
            formatDate,
            landlordInfo,
            getIcon,
            getLabel
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
