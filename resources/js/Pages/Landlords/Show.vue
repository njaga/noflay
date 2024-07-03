<template>
    <AppLayout title="Détails du bailleur">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-900 mb-8">
                    <i class="fas fa-user mr-2 text-indigo-600"></i>Détails du bailleur
                </h1>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ landlord.first_name }} {{
                            landlord.last_name }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Informations personnelles et détails du contrat.
                        </p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ landlord.last_name }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Prénom</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ landlord.first_name }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ landlord.address }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ landlord.phone }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ landlord.email }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Numéro CNI ou passeport</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ landlord.identity_number
                                    }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date d’expiration CNI ou passeport</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{
                                    landlord.identity_expiry_date }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Pourcentage à donner à l’agence</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{
                                    landlord.agency_percentage }}%</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Durée de contrat avec l’agence</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{
                                    landlord.contract_duration }} mois</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ landlord.company.name }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Pièces-jointes</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a :href="attachmentUrl" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-900">Télécharger</a>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-8">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Propriétés</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Liste des propriétés associées à ce bailleur.
                        </p>
                    </div>
                    <div class="border-t border-gray-200">
                        <ul>
                            <li v-for="property in landlord.properties" :key="property.id"
                                class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <div class="text-sm font-medium text-gray-500">{{ property.name }}</div>
                                <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.address }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-8">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Locataires</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Liste des locataires associés à ce bailleur.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <ul>
                            <li v-for="tenant in landlord.tenants" :key="tenant.id"
                                class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <div class="text-sm font-medium text-gray-500">{{ tenant.name }}</div>
                                <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ tenant.email }}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { props } = usePage();
const landlord = props.landlord;

const attachmentUrl = computed(() => {
    return landlord.attachments ? `/storage/${landlord.attachments}` : '';
});
</script>
