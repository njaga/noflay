<template>
    <AppLayout title="Ajouter une transaction">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Ajouter une transaction</h2>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Type de transaction</label>
                            <select id="type" v-model="form.type" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="commission">Commission</option>
                                <option value="rent">Loyer</option>
                                <option value="payment">Versement</option>
                                <option value="expense">Dépense</option>
                            </select>
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
                            <input type="number" id="amount" v-model="form.amount" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                        </div>
                        <div>
                            <label for="transaction_date" class="block text-sm font-medium text-gray-700">Date de la transaction</label>
                            <input type="date" id="transaction_date" v-model="form.transaction_date" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <input type="text" id="description" v-model="form.description" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                        <div>
                            <label for="property_id" class="block text-sm font-medium text-gray-700">Propriété</label>
                            <select id="property_id" v-model="form.property_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="" disabled>Sélectionnez une propriété</option>
                                <option v-for="property in properties" :key="property.id" :value="property.id">
                                    {{ property.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="landlord_id" class="block text-sm font-medium text-gray-700">Bailleur</label>
                            <select id="landlord_id" v-model="form.landlord_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="" disabled>Sélectionnez un bailleur</option>
                                <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
                                    {{ landlord.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700">Locataire</label>
                            <select id="tenant_id" v-model="form.tenant_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="" disabled>Sélectionnez un locataire</option>
                                <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                                    {{ tenant.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                            Ajouter
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
const properties = ref(props.properties || []);
const landlords = ref(props.landlords || []);
const tenants = ref(props.tenants || []);

const form = ref({
    type: '',
    amount: '',
    transaction_date: '',
    description: '',
    property_id: '',
    landlord_id: '',
    tenant_id: ''
});

const submit = () => {
    router.post(route('finances.store'), form.value, {
        onSuccess: () => {
            form.value = {
                type: '',
                amount: '',
                transaction_date: '',
                description: '',
                property_id: '',
                landlord_id: '',
                tenant_id: ''
            };
        }
    });
};
</script>
