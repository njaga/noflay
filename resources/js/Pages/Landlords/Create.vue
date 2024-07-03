<template>
    <AppLayout title="Créer un bailleur">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <h1
                        class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-8 animate-gradient">
                        Créer un bailleur
                    </h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="first_name" class="text-sm font-medium text-gray-700">Prénom</label>
                                <input id="first_name" v-model="form.first_name" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="last_name" class="text-sm font-medium text-gray-700">Nom</label>
                                <input id="last_name" v-model="form.last_name" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="address" class="text-sm font-medium text-gray-700">Adresse</label>
                                <input id="address" v-model="form.address" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="phone" class="text-sm font-medium text-gray-700">Téléphone</label>
                                <input id="phone" v-model="form.phone" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                                <input id="email" v-model="form.email" type="email" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="identity_number" class="text-sm font-medium text-gray-700">Numéro CNI ou
                                    passeport</label>
                                <input id="identity_number" v-model="form.identity_number" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="identity_expiry_date" class="text-sm font-medium text-gray-700">Date
                                    d'expiration CNI ou passeport</label>
                                <input id="identity_expiry_date" v-model="form.identity_expiry_date" type="date"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="agency_percentage" class="text-sm font-medium text-gray-700">Pourcentage à
                                    donner à l'agence</label>
                                <input id="agency_percentage" v-model="form.agency_percentage" type="number" step="0.01"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="contract_duration" class="text-sm font-medium text-gray-700">Durée de
                                    contrat avec l'agence (en mois)</label>
                                <input id="contract_duration" v-model="form.contract_duration" type="number" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div v-if="auth.isSuperAdmin" class="space-y-2">
                                <label for="company_id" class="text-sm font-medium text-gray-700">Entreprise</label>
                                <select id="company_id" v-model="form.company_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">Sélectionnez une entreprise</option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">{{
                                        company.name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2 sm:col-span-2">
                                <label for="attachments"
                                    class="text-sm font-medium text-gray-700">Pièces-jointes</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="attachments"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-indigo-300 border-dashed rounded-lg cursor-pointer bg-indigo-50 hover:bg-indigo-100 transition duration-300 ease-in-out">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-3 text-indigo-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                            <p class="mb-2 text-sm text-indigo-600"><span class="font-bold">Cliquez pour
                                                    télécharger</span> ou glissez-déposez</p>
                                            <p class="text-xs text-indigo-500">PNG, JPG, PDF jusqu'à 10MB</p>
                                        </div>
                                        <input id="attachments" type="file" @change="handleFileUpload" multiple
                                            class="hidden" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="form.processing"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                        </path>
                                    </svg>
                                    Enregistrer
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { auth, companies } = usePage().props;

const form = useForm({
    first_name: '',
    last_name: '',
    address: '',
    phone: '',
    email: '',
    identity_number: '',
    identity_expiry_date: '',
    agency_percentage: '',
    contract_duration: '',
    company_id: auth.isSuperAdmin ? '' : auth.user.company_id,
    attachments: [],
});

const handleFileUpload = (event) => {
    form.attachments = Array.from(event.target.files);
};

const submit = () => {
    form.post(route('landlords.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            alert('Bailleur créé avec succès');
        },
        onError: (errors) => {
            console.error(errors);
            alert('Une erreur est survenue lors de la création du bailleur');
        },
    });
};
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
</style>
