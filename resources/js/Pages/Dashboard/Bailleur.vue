<template>
    <AppLayout title="Tableau de bord - Bailleur">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-semibold mb-6">Bienvenue, Bailleur</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="p-6 bg-yellow-100 rounded-lg">
                                <h3 class="text-xl font-semibold">Propriétés</h3>
                                <p class="text-lg">{{ properties.length }}</p>
                            </div>
                            <div class="p-6 bg-green-100 rounded-lg">
                                <h3 class="text-xl font-semibold">Contrats</h3>
                                <p class="text-lg">{{ contracts.length }}</p>
                            </div>
                            <div class="p-6 bg-blue-100 rounded-lg">
                                <h3 class="text-xl font-semibold">Locataires</h3>
                                <p class="text-lg">{{ tenants.length }}</p>
                            </div>
                            <div class="p-6 bg-red-100 rounded-lg">
                                <h3 class="text-xl font-semibold">Paiements</h3>
                                <p class="text-lg">{{ payments.length }}</p>
                            </div>
                            <div class="p-6 bg-purple-100 rounded-lg">
                                <h3 class="text-xl font-semibold">Dépenses</h3>
                                <p class="text-lg">{{ expenses.length }}</p>
                            </div>
                        </div>
                        <div class="mt-10">
                            <h3 class="text-xl font-semibold mb-4">Détails des Propriétés</h3>
                            <ul>
                                <li v-for="property in properties" :key="property.id" class="mb-2">
                                    {{ property.name }} - {{ property.address }}
                                </li>
                                <li v-if="!properties.length">Aucune propriété trouvée.</li>
                            </ul>
                        </div>
                        <div class="mt-10">
                            <h3 class="text-xl font-semibold mb-4">Détails des Contrats</h3>
                            <ul>
                                <li v-for="contract in contracts" :key="contract.id" class="mb-2">
                                    Contrat {{ contract.id }} - {{ contract.start_date }} à {{ contract.end_date }}
                                </li>
                                <li v-if="!contracts.length">Aucun contrat trouvé.</li>
                            </ul>
                        </div>
                        <div class="mt-10">
                            <h3 class="text-xl font-semibold mb-4">Détails des Locataires</h3>
                            <ul>
                                <li v-for="tenant in tenants" :key="tenant.id" class="mb-2">
                                    {{ tenant.first_name }} {{ tenant.last_name }} - {{ tenant.email }}
                                </li>
                                <li v-if="!tenants.length">Aucun locataire trouvé.</li>
                            </ul>
                        </div>
                        <div class="mt-10">
                            <h3 class="text-xl font-semibold mb-4">Détails des Paiements</h3>
                            <ul>
                                <li v-for="payment in payments" :key="payment.id" class="mb-2">
                                    Paiement {{ payment.id }} - {{ payment.amount }} XOF - {{ payment.payment_date }}
                                </li>
                                <li v-if="!payments.length">Aucun paiement trouvé.</li>
                            </ul>
                        </div>
                        <div class="mt-10">
                            <h3 class="text-xl font-semibold mb-4">Détails des Dépenses</h3>
                            <ul>
                                <li v-for="expense in expenses" :key="expense.id" class="mb-2">
                                    Dépense {{ expense.id }} - {{ expense.amount }} XOF - {{ expense.expense_date }}
                                </li>
                                <li v-if="!expenses.length">Aucune dépense trouvée.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { props } = usePage();
const properties = ref(props.properties || []);
const contracts = ref(props.contracts || []);
const tenants = ref(props.tenants || []);
const payments = ref(props.payments || []);
const expenses = ref(props.expenses || []);
</script>
