<template>
    <AppLayout :title="'Créer un contrat de location pour ' + tenant.first_name + ' ' + tenant.last_name">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Créer un contrat de location</h2>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="contract_type" class="block text-sm font-medium text-gray-700">Type de contrat</label>
                            <select id="contract_type" v-model="form.contract_type" @change="resetForm"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="" disabled>Sélectionnez le type de contrat</option>
                                <option value="habitation">Habitation</option>
                                <option value="commercial">Commercial</option>
                            </select>
                        </div>
                        <div>
                            <label for="file_number" class="block text-sm font-medium text-gray-700">Numéro de dossier</label>
                            <input type="text" id="file_number" v-model="form.file_number"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début du contrat</label>
                            <input type="date" id="start_date" v-model="form.start_date"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                        </div>
                        <div v-if="form.contract_type === 'habitation'">
                            <div>
                                <label for="tenant_name" class="block text-sm font-medium text-gray-700">Nom du locataire</label>
                                <input type="text" id="tenant_name" v-model="tenantName" readonly
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            </div>
                            <div>
                                <label for="security_deposit" class="block text-sm font-medium text-gray-700">Montant de la caution</label>
                                <input type="number" id="security_deposit" v-model="form.security_deposit"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            </div>
                        </div>
                        <div v-if="form.contract_type === 'commercial'">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700">Société</label>
                                <input type="text" id="company_name" v-model="form.company_name"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            </div>
                            <div>
                                <label for="representative_name" class="block text-sm font-medium text-gray-700">Représentant de la société</label>
                                <input type="text" id="representative_name" v-model="form.representative_name"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            </div>
                            <div>
                                <label for="business_registration" class="block text-sm font-medium text-gray-700">Registre de commerce</label>
                                <input type="text" id="business_registration" v-model="form.business_registration"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            </div>
                            <div>
                                <label for="security_deposit" class="block text-sm font-medium text-gray-700">Montant de la caution</label>
                                <input type="number" id="security_deposit" v-model="form.security_deposit"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            </div>
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
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { props } = usePage();
const tenant = props.tenant;
const properties = props.properties;

const form = ref({
    contract_type: '',
    file_number: '',
    start_date: '',
    tenant_id: tenant.id,
    security_deposit: '',
    company_name: '',
    representative_name: '',
    business_registration: '',
});

const tenantName = ref(`${tenant.first_name} ${tenant.last_name}`);

const resetForm = () => {
    form.value = {
        ...form.value,
        security_deposit: '',
        company_name: '',
        representative_name: '',
        business_registration: '',
    };
};

const submit = () => {
    router.post(route('lease-contract.store'), form.value, {
        onSuccess: () => {
            form.value = {
                contract_type: '',
                file_number: '',
                start_date: '',
                tenant_id: tenant.id,
                security_deposit: '',
                company_name: '',
                representative_name: '',
                business_registration: '',
            };
            router.visit(route('tenants.index'));
        }
    });
};
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
</style>
