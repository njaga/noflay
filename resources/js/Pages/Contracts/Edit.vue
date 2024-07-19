<template>
    <AppLayout :title="`Modifier le contrat #${contract.id}`">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold text-indigo-700 mb-6">Modifier le contrat</h2>
                <form @submit.prevent="submit" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sélection du locataire -->
                        <div class="relative">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-1">Locataire</label>
                            <div class="relative">
                                <select id="tenant_id" v-model="form.tenant_id" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="" disabled>Sélectionnez un locataire</option>
                                    <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                                        {{ tenant.first_name }} {{ tenant.last_name }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Sélection de la propriété -->
                        <div class="relative">
                            <label for="property_id" class="block text-sm font-medium text-gray-700 mb-1">Propriété</label>
                            <div class="relative">
                                <select id="property_id" v-model="form.property_id" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="" disabled>Sélectionnez une propriété</option>
                                    <option v-for="property in properties" :key="property.id" :value="property.id">
                                        {{ property.name }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-house-add-fill text-gray-400 w-5 h-5" viewBox="0 0 16 16">
                                        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 1 1-1 0v-1h-1a.5.5 0 1 1 0-1h1v-1a.5.5 0 0 1 1 0" />
                                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z" />
                                        <path d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Champs spécifiques au contrat commercial -->
                        <div v-if="form.contract_type === 'commercial'" class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la société</label>
                                <input type="text" id="company_name" v-model="form.company_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label for="representative_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du représentant</label>
                                <input type="text" id="representative_name" v-model="form.representative_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label for="trade_register" class="block text-sm font-medium text-gray-700 mb-1">Registre de commerce</label>
                                <input type="text" id="trade_register" v-model="form.trade_register" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                        </div>

                        <!-- Date de début et durée -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début du contrat</label>
                            <input type="date" id="start_date" v-model="form.start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Durée (mois)</label>
                            <input type="number" id="duration" v-model="form.duration" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required />
                        </div>

                        <!-- Informations financières -->
                        <div>
                            <label for="caution_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant de la caution</label>
                            <div class="relative">
                                <input type="number" id="caution_amount" v-model="form.caution_amount" class="mt-1 block w-full pl-12 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" readonly />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">CFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Numéro de dossier -->
                        <div>
                            <label for="file_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de dossier</label>
                            <input type="text" id="file_number" v-model="form.file_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" readonly />
                        </div>

                        <!-- Sélection de l'entreprise (pour super admin) -->
                        <div v-if="isSuperAdmin" class="md:col-span-2">
                            <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Entreprise</label>
                            <select id="company_id" v-model="form.company_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="" disabled>Sélectionnez une entreprise</option>
                                <option v-for="company in companies" :key="company.id" :value="company.id">
                                    {{ company.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Enregistrer les modifications
                        </button>
                        <Link :href="route('contracts.index')" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Annuler
                        </Link>
                    </div>
                </form>
            </div>
        </div>
        <Modal :show="showSuccessModal" @close="closeSuccessModal">
            <div class="p-6 bg-white rounded-lg shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Modification réussie</h3>
                <p class="text-sm text-gray-500 mb-4">Les modifications du contrat ont été enregistrées avec succès.</p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="closeSuccessModal" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">Fermer</button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

const { props } = usePage();
const contract = ref(props.contract);
const tenants = ref(props.tenants);
const properties = ref(props.properties);
const companies = ref(props.companies);
const showSuccessModal = ref(false);
const form = ref({
    tenant_id: contract.value.tenant_id,
    property_id: contract.value.property_id,
    contract_type: contract.value.contract_type,
    start_date: contract.value.start_date,
    duration: contract.value.duration,
    caution_amount: contract.value.caution_amount,
    file_number: contract.value.file_number,
    company_id: props.auth?.user?.roles?.includes('super_admin') ? contract.value.company_id : props.auth.user.company_id,
    company_name: contract.value.company_name || '',
    representative_name: contract.value.representative_name || '',
    trade_register: contract.value.trade_register || '',
});

const isSuperAdmin = computed(() => props.auth?.user?.roles?.includes('super_admin'));

const updateCautionAmount = () => {
    const selectedProperty = properties.value.find(property => property.id === form.value.property_id);
    if (selectedProperty) {
        form.value.caution_amount = selectedProperty.price * 2;
    }
};

const submit = () => {
    router.put(route('contracts.update', contract.value.id), form.value, {
        onSuccess: () => {
            showSuccessModal.value = true;
        }
    });
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
};

onMounted(() => {
    updateCautionAmount();
});

watch(() => form.value.property_id, updateCautionAmount);
</script>
