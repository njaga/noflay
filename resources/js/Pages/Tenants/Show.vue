<template>
    <AppLayout title="Profil du locataire">
        <div class="bg-gradient-to-br min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
                    <!-- Contenu principal -->
                    <div class="mt-6 mb-6 px-6 py-8">
                        <h1 class="text-3xl font-bold text-center text-gray-800">
                            {{ tenant.first_name }} {{ tenant.last_name }}
                        </h1>

                        <!-- Onglets d'information -->
                        <div class="mt-8">
                            <Tab :titles="['Coordonnées', 'Informations personnelles', 'Propriétés', 'Pièces jointes']" :default-index="0">
                                <template v-slot:tab0>
                                    <div class="space-y-4">
                                        <InfoCard icon="fa-phone" title="Téléphone" :value="tenant.phone_number" />
                                        <InfoCard icon="fa-envelope" title="Email" :value="tenant.email" />
                                        <InfoCard icon="fa-map-marker-alt" title="Adresse" :value="tenant.address" />
                                    </div>
                                </template>
                                <template v-slot:tab1>
                                    <div class="space-y-4">
                                        <InfoCard icon="fa-id-card" title="N° Carte d'identité"
                                            :value="tenant.id_card_number" />
                                        <InfoCard icon="fa-calendar-alt" title="Expiration Carte d'identité"
                                            :value="formatDate(tenant.id_card_expiration_date)" />
                                    </div>
                                </template>
                                <template v-slot:tab2>
                                    <div class="space-y-4">
                                        <div v-if="tenant.contracts.length">
                                            <div v-for="contract in tenant.contracts" :key="contract.id">
                                                <InfoCard icon="fa-home" title="Nom de la propriété"
                                                    :value="contract.property.name" />
                                                <InfoCard icon="fa-map" title="Adresse de la propriété"
                                                    :value="contract.property.address" />
                                                <InfoCard icon="fa-user-tie" title="Nom du bailleur"
                                                    :value="`${contract.property.landlord.first_name} ${contract.property.landlord.last_name}`" />
                                            </div>
                                        </div>
                                        <p v-else class="text-gray-500 italic">Aucune propriété associée</p>
                                    </div>
                                </template>
                                <template v-slot:tab3>
        <div class="space-y-4">
            <div v-if="tenant.formatted_attachments && tenant.formatted_attachments.length">
                <div v-for="(attachment, index) in tenant.formatted_attachments" :key="index" class="flex items-center justify-between p-3 bg-gray-100 rounded-lg mb-2">
                    <span class="text-sm font-medium text-gray-700">{{ attachment.name }}</span>
                    <a :href="attachment.url" target="_blank" class="text-indigo-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-download mr-2"></i> Télécharger
                    </a>
                </div>
            </div>
            <p v-else class="text-gray-500 italic">Aucune pièce jointe</p>
        </div>
    </template>
                            </Tab>
                        </div>

                        <!-- Actions -->
                        <div class="mt-12 flex justify-center space-x-4">
                            <Link :href="route('tenants.edit', tenant.id)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                <i class="fas fa-edit mr-2"></i> Modifier le profil
                            </Link>
                            <Button @click="showDeleteModal = true" variant="danger" size="md"
                                class="flex items-center">
                                <i class="fas fa-trash-alt mr-2"></i> Supprimer le compte
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation de suppression -->
        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Confirmer la suppression
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Êtes-vous sûr de vouloir supprimer ce compte de locataire ? Cette action est irréversible.
                </p>

                <div class="mt-6 flex justify-end">
                    <Button @click="showDeleteModal = false" variant="outline" size="sm" class="mr-3">
                        Annuler
                    </Button>
                    <Button @click="confirmDelete" variant="danger" size="sm">
                        Supprimer
                    </Button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Tab from '@/Components/Tab.vue';
import InfoCard from '@/Components/InfoCard.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    tenant: Object,
});

const emit = defineEmits(['delete']);

const showDeleteModal = ref(false);

const formatDate = (date) => new Date(date).toLocaleDateString();

const confirmDelete = () => {
    showDeleteModal.value = false;
    emit('delete');
};
</script>
