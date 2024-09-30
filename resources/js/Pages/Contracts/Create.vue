<template>
    <AppLayout title="Créer un contrat">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <h1
                        class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-8 animate-gradient">
                        Nouveau dossier locatif
                    </h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Onglets pour les types de contrat -->
                            <div class="sm:col-span-2">
                                <div class="flex space-x-4 mb-4">
                                    <button type="button" @click="setContractType('habitation')"
                                        :class="['px-4 py-2 rounded-lg transition duration-200 ease-in-out transform hover:scale-105',
                                            form.contract_type === 'habitation' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700']">
                                        Habitation
                                    </button>
                                    <button type="button" @click="setContractType('commercial')"
                                        :class="['px-4 py-2 rounded-lg transition duration-200 ease-in-out transform hover:scale-105',
                                            form.contract_type === 'commercial' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700']">
                                        Commercial
                                    </button>
                                </div>
                            </div>

                            <!-- Sélection du locataire -->
                            <div class="space-y-2">
                                <label for="tenant_id" class="text-sm font-medium text-gray-700">Locataire</label>
                                <input v-if="selectedTenant" type="text"
                                    :value="selectedTenant.first_name + ' ' + selectedTenant.last_name" readonly
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100" />
                                <select v-else id="tenant_id" v-model="form.tenant_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="" disabled>Sélectionnez un locataire</option>
                                    <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                                        {{ tenant.first_name }} {{ tenant.last_name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Sélection de la propriété -->
                            <div class="space-y-2">
                                <label for="property_id" class="text-sm font-medium text-gray-700">Propriété</label>
                                <select id="property_id" v-model="form.property_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="" disabled>Sélectionnez une propriété</option>
                                    <option v-for="property in availableProperties" :key="property.id"
                                        :value="property.id">
                                        {{ property.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Champs spécifiques au contrat commercial -->
                            <template v-if="form.contract_type === 'commercial'">
                                <div class="space-y-2">
                                    <label for="company" class="text-sm font-medium text-gray-700">Nom de la
                                        société</label>
                                    <input type="text" id="company" v-model="form.company"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                </div>
                                <div class="space-y-2">
                                    <label for="representative" class="text-sm font-medium text-gray-700">Nom du
                                        représentant</label>
                                    <input type="text" id="representative" v-model="form.representative"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                </div>
                                <div class="space-y-2">
                                    <label for="trade_register" class="text-sm font-medium text-gray-700">Registre de
                                        commerce</label>
                                    <input type="text" id="trade_register" v-model="form.trade_register"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                </div>
                            </template>

                            <!-- Date de début et durée -->
                            <div class="space-y-2">
                                <label for="start_date" class="text-sm font-medium text-gray-700">Date de début du
                                    contrat</label>
                                <input type="date" id="start_date" v-model="form.start_date" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>
                            <div class="space-y-2">
                                <label for="duration" class="text-sm font-medium text-gray-700">Durée (mois)</label>
                                <input type="number" id="duration" v-model="form.duration" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <!-- Informations financières -->
                            <div class="space-y-2">
                                <label for="caution_amount" class="text-sm font-medium text-gray-700">Montant de la
                                    caution</label>
                                <input type="number" id="caution_amount" v-model="form.caution_amount" readonly
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <!-- Numéro de dossier -->
                            <div class="space-y-2">
                                <label for="file_number" class="text-sm font-medium text-gray-700">Numéro de
                                    dossier</label>
                                <input type="text" id="file_number" v-model="form.file_number" readonly
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <!-- Sélection de l'entreprise (pour super admin) -->
                            <div v-if="isSuperAdmin" class="sm:col-span-2 space-y-2">
                                <label for="company_id" class="text-sm font-medium text-gray-700">Entreprise</label>
                                <select id="company_id" v-model="form.company_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="" disabled>Sélectionnez une entreprise</option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">
                                        {{ company.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="form.processing"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                        </path>
                                    </svg>
                                    Créer le dossier
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de succès -->
        <Transition name="modal">
            <div v-if="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                        <div>
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Dossier de location créé avec succès
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Le nouveau dossier de location a été ajouté avec succès.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <button type="button" @click="closeModal"
                                class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const { props } = usePage();
const tenants = ref(props.tenants || []);
const selectedTenant = ref(page.props.selectedTenant);
const properties = ref(props.properties || []);
const companies = ref(props.companies || []);

const form = useForm({
    tenant_id: selectedTenant.value ? selectedTenant.value.id : '',
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

const showSuccessModal = ref(false);

const isSuperAdmin = computed(() => props.auth?.user?.roles?.includes('super_admin'));

const availableProperties = computed(() => {
    return properties.value.filter(property => property.available_count > 0 && property.company_id === form.company_id);
});

const setContractType = (type) => {
    form.contract_type = type;
};

const updateFinancialDetails = () => {
    const selectedProperty = properties.value.find(property => property.id === form.property_id);
    if (selectedProperty) {
        form.rent_amount = selectedProperty.price;
        form.caution_amount = selectedProperty.price * 2;

        const landlord = selectedProperty.landlord;
        if (landlord) {
            form.landlord_id = landlord.id;
            form.commission_amount = selectedProperty.price * (landlord.agency_percentage / 100);
        }
    }
};

const generateFileNumber = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    form.file_number = `DOS-${year}${month}-${Math.floor(Math.random() * 10000)}`;
};

const calculateEndDate = () => {
    if (form.start_date && form.duration) {
        const startDate = new Date(form.start_date);
        startDate.setMonth(startDate.getMonth() + parseInt(form.duration));
        form.end_date = startDate.toISOString().split('T')[0];
    } else {
        form.end_date = '';
    }
};

const submit = () => {
    if (form.contract_type !== 'commercial') {
        delete form.company;
        delete form.representative;
        delete form.trade_register;
    }

    form.post(route('contracts.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            showSuccessModal.value = true;
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

const closeModal = () => {
    showSuccessModal.value = false;
    router.visit(route('contracts.index'));
};

onMounted(() => {
    generateFileNumber();
    if (selectedTenant.value) {
        // Pré-remplir d'autres champs si nécessaire
    }
});

watch(() => form.property_id, updateFinancialDetails);
watch(() => [form.start_date, form.duration], calculateEndDate);
</script>

<style scoped>
@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 5s ease infinite;
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
