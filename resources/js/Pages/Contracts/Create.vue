<template>
    <AppLayout title="Créer un contrat">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Créer un contrat</h2>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="tenant" class="block text-sm font-medium text-gray-700">Locataire</label>
                            <input type="text" id="tenant" v-model="tenantName"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" readonly />
                        </div>
                        <div>
                            <label for="property_id" class="block text-sm font-medium text-gray-700">Propriété</label>
                            <select id="property_id" v-model="form.property_id"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="" disabled>Sélectionnez une propriété</option>
                                <option v-for="property in properties" :key="property.id" :value="property.id">
                                    {{ property.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="contract_type" class="block text-sm font-medium text-gray-700">Type de contrat</label>
                            <select id="contract_type" v-model="form.contract_type"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="" disabled>Sélectionnez un type de contrat</option>
                                <option value="habitation">Habitation</option>
                                <option value="commercial">Commercial</option>
                            </select>
                        </div>
                        <div v-if="form.contract_type === 'commercial'">
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Nom de la société</label>
                            <input type="text" id="company_name" v-model="form.company_name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                        <div v-if="form.contract_type === 'commercial'">
                            <label for="representative_name" class="block text-sm font-medium text-gray-700">Nom du représentant</label>
                            <input type="text" id="representative_name" v-model="form.representative_name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                        <div v-if="form.contract_type === 'commercial'">
                            <label for="trade_register" class="block text-sm font-medium text-gray-700">Registre de commerce</label>
                            <input type="text" id="trade_register" v-model="form.trade_register"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début du contrat</label>
                            <input type="date" id="start_date" v-model="form.start_date"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                        </div>
                        <div>
                            <label for="file_number" class="block text-sm font-medium text-gray-700">Numéro de dossier</label>
                            <input type="text" id="file_number" v-model="form.file_number"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                        </div>
                        <div>
                            <label for="caution_amount" class="block text-sm font-medium text-gray-700">Montant de la caution</label>
                            <input type="number" id="caution_amount" v-model="form.caution_amount"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" readonly />
                        </div>
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700">Durée (mois)</label>
                            <input type="number" id="duration" v-model="form.duration"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                        </div>
                        <div v-if="isSuperAdmin">
                            <label for="company_id" class="block text-sm font-medium text-gray-700">Entreprise</label>
                            <select id="company_id" v-model="form.company_id"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="" disabled>Sélectionnez une entreprise</option>
                                <option v-for="company in companies" :key="company.id" :value="company.id">
                                    {{ company.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                            Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { props } = usePage();
const companies = ref(props.companies || []);
const properties = ref(props.properties || []);
const tenant = ref(props.tenant);

const form = ref({
    tenant_id: tenant.value.id,
    property_id: '',
    contract_type: '',
    start_date: '',
    file_number: '',
    caution_amount: 0,
    company_name: '',
    representative_name: '',
    trade_register: '',
    company_id: props.auth?.user?.roles?.includes('super_admin') ? '' : props.auth.user.company_id,
    duration: 0,
    deposit_amount: 0,
});

const tenantName = computed(() => `${tenant.value.first_name} ${tenant.value.last_name}`);
const isSuperAdmin = computed(() => props.auth?.user?.roles?.includes('super_admin'));

const updateCautionAmount = () => {
    const selectedProperty = properties.value.find(property => property.id === form.value.property_id);
    if (selectedProperty) {
        form.value.caution_amount = selectedProperty.price * 2;
    }
};

const submit = () => {
    // Retirez les champs optionnels si le contrat n'est pas commercial
    if (form.value.contract_type !== 'commercial') {
        delete form.value.company_name;
        delete form.value.representative_name;
        delete form.value.trade_register;
    }

    router.post(route('contracts.store'), form.value, {
        onSuccess: () => {
            form.value = {
                tenant_id: tenant.value.id,
                property_id: '',
                contract_type: '',
                start_date: '',
                file_number: '',
                caution_amount: 0,
                company_name: '',
                representative_name: '',
                trade_register: '',
                company_id: isSuperAdmin.value ? '' : props.auth.user.company_id,
                duration: 0,
                deposit_amount: 0,
            };
        }
    });
};

watch(() => form.value.property_id, updateCautionAmount);
</script>
