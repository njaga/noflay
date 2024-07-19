<template>
    <AppLayout title="Nouveau Paiement">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold text-indigo-700 mb-6">Nouveau Paiement</h2>
                <form @submit.prevent="submit" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sélection du locataire -->
                        <div class="relative">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-1">Locataire</label>
                            <div class="relative">
                                <select id="tenant_id" v-model="form.tenant_id" @change="fetchContracts" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="" disabled>Sélectionnez un locataire</option>
                                    <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                                        {{ tenant.first_name }} {{ tenant.last_name }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Sélection du contrat -->
                        <div class="relative">
                            <label for="contract_id" class="block text-sm font-medium text-gray-700 mb-1">Contrat</label>
                            <div class="relative">
                                <select id="contract_id" v-model="form.contract_id" @change="fetchRentAmount" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="" disabled>Sélectionnez un contrat</option>
                                    <option v-for="contract in filteredContracts" :key="contract.id" :value="contract.id">
                                        {{ contract.id }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-file-contract text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Mois du loyer -->
                        <div class="relative">
                            <label for="payment_month" class="block text-sm font-medium text-gray-700 mb-1">Mois du loyer</label>
                            <input type="month" id="payment_month" v-model="form.payment_month" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required />
                        </div>

                        <!-- Type de paiement -->
                        <div class="relative">
                            <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">Type de paiement</label>
                            <select id="payment_type" v-model="form.payment_type" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="" disabled>Sélectionnez un type de paiement</option>
                                <option value="integral">Somme intégrale</option>
                                <option value="advance">Avance</option>
                                <option value="balance">Reliquat</option>
                            </select>
                        </div>

                        <!-- Montant de location -->
                        <div>
                            <label for="rent_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant de Location</label>
                            <div class="relative">
                                <input type="number" id="rent_amount" v-model="form.rent_amount" class="mt-1 block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" readonly />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">CFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Montant de l'avance -->
                        <div v-if="form.payment_type === 'advance' || form.payment_type === 'balance'">
                            <label for="advance_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant de l'Avance</label>
                            <div class="relative">
                                <input type="number" id="advance_amount" v-model="form.advance_amount" @input="calculateRemainingAmount" class="mt-1 block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">CFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Montant total payé -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Montant Total Payé</label>
                            <div class="relative">
                                <input type="number" id="amount" v-model="form.amount" class="mt-1 block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" readonly />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">CFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date de Paiement -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Date de Paiement</label>
                            <input type="date" id="payment_date" v-model="form.payment_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required />
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Enregistrer le paiement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { props } = usePage();
const tenants = ref(props.tenants || []);
const contracts = ref(props.contracts || []);
const filteredContracts = ref([]);

const form = ref({
    tenant_id: '',
    contract_id: '',
    rent_amount: 0,
    advance_amount: 0,
    amount: 0,
    payment_date: '',
    payment_month: '',
    payment_type: '',
    tva_amount: 0
});

const fetchContracts = () => {
    filteredContracts.value = contracts.value.filter(contract => contract.tenant_id === form.value.tenant_id);
    form.value.contract_id = '';
    form.value.rent_amount = 0;
    form.value.advance_amount = 0;
    form.value.amount = 0;
};

const fetchRentAmount = () => {
    const selectedContract = contracts.value.find(contract => contract.id === form.value.contract_id);
    if (selectedContract) {
        form.value.rent_amount = selectedContract.rent_amount;
        form.value.amount = form.value.rent_amount - form.value.advance_amount;
    }
};

const calculateRemainingAmount = () => {
    form.value.amount = form.value.rent_amount - form.value.advance_amount;
    form.value.tva_amount = form.value.amount * 0.18;
    form.value.amount += form.value.tva_amount;
};

const submit = () => {
    router.post(route('payments.store'), form.value, {
        onSuccess: () => {
            form.value = {
                tenant_id: '',
                contract_id: '',
                rent_amount: 0,
                advance_amount: 0,
                amount: 0,
                payment_date: '',
                payment_month: '',
                payment_type: '',
                tva_amount: 0
            };
        }
    });
};
</script>
