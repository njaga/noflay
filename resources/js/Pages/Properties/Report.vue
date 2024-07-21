<template>
    <AppLayout :title="`${property.name} - Rapport`">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold text-indigo-800 mb-8">Rapport de la propriété : {{ property.name }}</h1>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Informations sur la propriété</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Détails de la propriété et rapport financier.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Type de propriété</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.property_type }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.address }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Bailleur</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ property.landlord ? `${property.landlord.first_name} ${property.landlord.last_name}` : 'N/A' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ property.company ? property.company.name : 'N/A' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Locataires</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Liste des locataires actuels.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <ul class="divide-y divide-gray-200">
                            <li v-for="contract in property.contracts" :key="contract.id" class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        {{ contract.tenant ? `${contract.tenant.first_name} ${contract.tenant.last_name}` : 'N/A' }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="text-sm font-medium text-gray-500">{{ contract.start_date }} - {{ contract.end_date }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-home mr-1"></i>
                                            {{ contract.property ? contract.property.name : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <i class="fas fa-money-bill-wave mr-1"></i>
                                        {{ contract.rent_amount }} XOF
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Paiements</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Historique des paiements effectués.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <ul class="divide-y divide-gray-200">
                            <li v-for="payment in property.payments" :key="payment.id" class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">{{ payment.amount }} XOF</p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="text-sm font-medium text-gray-500">{{ payment.payment_date }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-receipt mr-1"></i>
                                            {{ payment.description }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ payment.tenant ? `${payment.tenant.first_name} ${payment.tenant.last_name}` : 'N/A' }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const { props } = usePage();
const property = props.property;
</script>
