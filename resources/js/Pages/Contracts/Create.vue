<template>
    <AppLayout title="Créer un contrat">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold text-indigo-700 mb-6">Nouveau dossier locatif</h2>
                <form @submit.prevent="submit" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Onglets pour les types de contrat -->
                        <div class="md:col-span-2">
                            <div class="flex space-x-4 mb-4">
                                <button type="button" @click="setContractType('habitation')"
                                    :class="['px-4 py-2 rounded-md transition', form.contract_type === 'habitation' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700']">
                                    Habitation
                                </button>
                                <button type="button" @click="setContractType('commercial')"
                                    :class="['px-4 py-2 rounded-md transition', form.contract_type === 'commercial' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700']">
                                    Commercial
                                </button>
                            </div>
                        </div>

                        <!-- Afficher le nom du locataire -->
                        <div v-if="tenant" class="relative">
                            <label for="tenant_name" class="block text-sm font-medium text-gray-700 mb-1">Locataire</label>
                            <input type="text" id="tenant_name" :value="tenantName" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" disabled />
                        </div>

                        <!-- Sélection du locataire (si pas de tenantId dans l'URL) -->
                        <div v-else class="relative">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-1">Locataire</label>
                            <div class="relative">
                                <select id="tenant_id" v-model="form.tenant_id"
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required @change="fetchTenantName">
                                    <option value="" disabled>Sélectionnez un locataire</option>
                                    <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                                        {{ tenant.first_name }} {{ tenant.last_name }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Sélection de la propriété -->
                        <div class="relative">
                            <label for="property_id" class="block text-sm font-medium text-gray-700 mb-1">Propriété</label>
                            <div class="relative">
                                <select id="property_id" v-model="form.property_id"
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required @change="updateFinancialDetails">
                                    <option value="" disabled>Sélectionnez une propriété</option>
                                    <option v-for="property in availableProperties" :key="property.id" :value="property.id">
                                        {{ property.name }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        class="bi bi-house-add-fill text-gray-400 w-5 h-5" viewBox="0 0 16 16">
                                        <path
                                            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 1 1 0-1h1v-1a.5.5 0 0 1 1 0" />
                                        <path
                                            d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z" />
                                        <path
                                            d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Champs spécifiques au contrat commercial -->
                        <div v-if="form.contract_type === 'commercial'" class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Nom de la société</label>
                                <input type="text" id="company" v-model="form.company" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label for="representative" class="block text-sm font-medium text-gray-700 mb-1">Nom du représentant</label>
                                <input type="text" id="representative" v-model="form.representative" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
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

                    <!-- Bouton de soumission -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Créer le dossier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
    <!-- Modal -->
    <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10" v-if="modalSuccess">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10" v-else>
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ modalSuccess ? 'Succès' : 'Échec' }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ modalSuccess ? 'Dossier de location créé avec succès.' : 'Échec lors de la création du dossier location.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

const { props } = usePage();
const tenants = ref(props.tenants || []);
const properties = ref(props.properties || []);
const companies = ref(props.companies || []);
const tenant = ref(props.tenant || null);
const tenantName = ref('');

const form = ref({
    tenant_id: tenant.value ? tenant.value.id : '',
    property_id: '',
    contract_type: 'habitation',
    start_date: '',
    end_date: '',
    file_number: '',
    company: '',
    representative: '',
    trade_register: '',
    company_id: props.auth?.user?.roles?.includes('super_admin') ? '' : props.auth.user.company_id,
    duration: 0,
    rent_amount: 0,
    commission_amount: 0,
    landlord_id: '',
    caution_amount: 0
});

const showModal = ref(false);
const modalSuccess = ref(true);

const isSuperAdmin = computed(() => props.auth?.user?.roles?.includes('super_admin'));

const availableProperties = computed(() => {
    return properties.value.filter(property => property.available_count > 0 && property.company_id === form.value.company_id);
});

const setContractType = (type) => {
    form.value.contract_type = type;
};

const updateFinancialDetails = () => {
    const selectedProperty = properties.value.find(property => property.id === form.value.property_id);
    if (selectedProperty) {
        form.value.rent_amount = selectedProperty.price;
        form.value.caution_amount = selectedProperty.price * 2;

        // Calculer la commission de l'agence immobilière
        const landlord = selectedProperty.landlord;
        if (landlord) {
            form.value.landlord_id = landlord.id;
            form.value.commission_amount = selectedProperty.price * (landlord.agency_percentage / 100);
        }
    }
};

const generateFileNumber = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    form.value.file_number = `DOS-${year}${month}-${Math.floor(Math.random() * 10000)}`;
};

const calculateEndDate = () => {
    if (form.value.start_date && form.value.duration) {
        const startDate = new Date(form.value.start_date);
        startDate.setMonth(startDate.getMonth() + parseInt(form.value.duration));
        form.value.end_date = startDate.toISOString().split('T')[0];
    } else {
        form.value.end_date = '';
    }
};

const submit = async () => {
    if (form.value.contract_type !== 'commercial') {
        delete form.value.company;
        delete form.value.representative;
        delete form.value.trade_register;
    }

    // Include the computed caution amount in the form submission
    const formData = {
        ...form.value,
        caution_amount: form.value.caution_amount
    };

    try {
        await router.post(route('contracts.store'), formData);
        modalSuccess.value = true;
    } catch (error) {
        modalSuccess.value = false;
    } finally {
        showModal.value = true;
        form.value = {
            tenant_id: tenant.value ? tenant.value.id : '',
            property_id: '',
            contract_type: 'habitation',
            start_date: '',
            end_date: '',
            file_number: '',
            company: '',
            representative: '',
            trade_register: '',
            company_id: isSuperAdmin.value ? '' : props.auth.user.company_id,
            duration: 0,
            rent_amount: 0,
            commission_amount: 0,
            landlord_id: '',
            caution_amount: 0
        };
        generateFileNumber();
    }
};

const fetchTenantName = async () => {
    if (tenant.value) {
        tenantName.value = `${tenant.value.first_name} ${tenant.value.last_name}`;
    } else if (form.value.tenant_id) {
        try {
            const response = await axios.get(`/api/tenants/${form.value.tenant_id}`);
            tenantName.value = `${response.data.first_name} ${response.data.last_name}`;
        } catch (error) {
            console.error('Erreur lors de la récupération du nom du locataire:', error);
        }
    }
};

const closeModal = () => {
    showModal.value = false;
    router.visit(route('contracts.index'));
};

onMounted(() => {
    generateFileNumber();
    fetchTenantName();
});

watch(() => form.value.property_id, updateFinancialDetails);
watch(() => [form.value.start_date, form.value.duration], calculateEndDate);
</script>

<style scoped>
/* Ajoutez vos styles personnalisés ici si nécessaire */
</style>
