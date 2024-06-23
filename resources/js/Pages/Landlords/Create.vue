<template>
    <AppLayout title="Créer un bailleur">
        <div class="py-12 bg-gray-100">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Créer un nouveau bailleur</h1>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                                    <input type="text" id="prenom" v-model="form.prenom"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.prenom" class="text-red-500 text-sm mt-1">{{
                                        form.errors.prenom }}</div>
                                </div>

                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input type="text" id="nom" v-model="form.nom"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.nom" class="text-red-500 text-sm mt-1">{{ form.errors.nom }}
                                    </div>
                                </div>

                                <div>
                                    <label for="telephone" class="block text-sm font-medium text-gray-700">Numéro de
                                        téléphone</label>
                                    <input type="tel" id="telephone" v-model="form.telephone"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.telephone" class="text-red-500 text-sm mt-1">{{
                                        form.errors.telephone }}</div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="email" v-model="form.email"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email
                                        }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <textarea id="adresse" v-model="form.adresse" rows="3"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required></textarea>
                                    <div v-if="form.errors.adresse" class="text-red-500 text-sm mt-1">{{
                                        form.errors.adresse }}</div>
                                </div>

                                <div>
                                    <label for="numero_cni_passport"
                                        class="block text-sm font-medium text-gray-700">Numéro CNI ou passport</label>
                                    <input type="text" id="numero_cni_passport" v-model="form.numero_cni_passport"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.numero_cni_passport" class="text-red-500 text-sm mt-1">{{
                                        form.errors.numero_cni_passport }}</div>
                                </div>

                                <div>
                                    <label for="date_expiration" class="block text-sm font-medium text-gray-700">Date
                                        d'expiration</label>
                                    <input type="date" id="date_expiration" v-model="form.date_expiration"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.date_expiration" class="text-red-500 text-sm mt-1">{{
                                        form.errors.date_expiration }}</div>
                                </div>

                                <div>
                                    <label for="pourcentage_agence"
                                        class="block text-sm font-medium text-gray-700">Pourcentage agence</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" id="pourcentage_agence" v-model="form.pourcentage_agence"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                            min="0" max="100" step="0.01" required>
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.pourcentage_agence" class="text-red-500 text-sm mt-1">{{
                                        form.errors.pourcentage_agence }}</div>
                                </div>

                                <div v-if="companies.length > 1">
                                    <label for="company_id"
                                        class="block text-sm font-medium text-gray-700">Entreprise</label>
                                    <select id="company_id" v-model="form.company_id"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        required>
                                        <option v-for="company in companies" :key="company.id" :value="company.id">
                                            {{ company.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.company_id" class="text-red-500 text-sm mt-1">{{
                                        form.errors.company_id }}</div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="button" @click="cancel"
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    :disabled="form.processing">
                                    {{ form.processing ? 'Création en cours...' : 'Créer le bailleur' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Bailleur créé avec succès
                </h2>
                <p class="text-sm text-gray-600 mb-4">
                    Le bailleur "{{ createdLandlordName }}" a été ajouté à la base de données.
                </p>
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="closeModal"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Fermer
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    companies: {
        type: Array,
        required: true
    }
});

const form = useForm({
    prenom: '',
    nom: '',
    telephone: '',
    email: '',
    adresse: '',
    numero_cni_passport: '',
    date_expiration: '',
    pourcentage_agence: '',
    company_id: props.companies.length === 1 ? props.companies[0].id : '',
});

const showModal = ref(false);
const createdLandlordName = ref('');

const submit = () => {
    form.post(route('landlords.store'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            createdLandlordName.value = `${form.prenom} ${form.nom}`;
            showModal.value = true;
            form.reset();
        },
        onError: (errors) => {
            console.error('Errors:', errors);
        },
    });
};

const cancel = () => {
    window.history.back()
};

const closeModal = () => {
    showModal.value = false;
};
</script>
