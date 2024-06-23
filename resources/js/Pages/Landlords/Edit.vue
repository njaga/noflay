<template>
    <AppLayout :title="'Modifier ' + landlord.firstName + ' ' + landlord.lastName">
        <div class="py-12 bg-gray-100">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Modifier le bailleur</h1>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <div>
                                    <label for="firstName"
                                        class="block text-sm font-medium text-gray-700">Prénom</label>
                                    <input type="text" id="firstName" v-model="form.firstName"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.firstName" class="text-red-500 text-sm mt-1">{{
                                        form.errors.firstName }}</div>
                                </div>

                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input type="text" id="lastName" v-model="form.lastName"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.lastName" class="text-red-500 text-sm mt-1">{{
                                        form.errors.lastName }}</div>
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Numéro de
                                        téléphone</label>
                                    <input type="tel" id="phone" v-model="form.phone"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone
                                        }}</div>
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
                                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <textarea id="address" v-model="form.address" rows="3"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required></textarea>
                                    <div v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{
                                        form.errors.address }}</div>
                                </div>

                                <div>
                                    <label for="idNumber" class="block text-sm font-medium text-gray-700">Numéro CNI ou
                                        passport</label>
                                    <input type="text" id="idNumber" v-model="form.idNumber"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.idNumber" class="text-red-500 text-sm mt-1">{{
                                        form.errors.idNumber }}</div>
                                </div>

                                <div>
                                    <label for="expirationDate" class="block text-sm font-medium text-gray-700">Date
                                        d'expiration</label>
                                    <input type="date" id="expirationDate" v-model="form.
                                        expirationDate" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    <div v-if="form.errors.expirationDate" class="text-red-500 text-sm mt-1">{{
                                        form.errors.expirationDate }}</div>
                                </div>

                                <div>
                                    <label for="agencyPercentage"
                                        class="block text-sm font-medium text-gray-700">Pourcentage agence</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" id="agencyPercentage" v-model="form.agencyPercentage"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                            min="0" max="100" step="0.01" required>
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.agencyPercentage" class="text-red-500 text-sm mt-1">{{
                                        form.errors.agencyPercentage }}</div>
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
                                    {{ form.processing ? 'Modification en cours...' : 'Modifier le bailleur' }}
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
                    Bailleur modifié avec succès
                </h2>
                <p class="text-sm text-gray-600 mb-4">
                    Les informations du bailleur "{{ updatedLandlordName }}" ont été mises à jour.
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
    landlord: Object,
});

const form = useForm({
    firstName: props.landlord.firstName,
    lastName: props.landlord.lastName,
    phone: props.landlord.phone,
    email: props.landlord.email,
    address: props.landlord.address,
    idNumber: props.landlord.idNumber,
    expirationDate: props.landlord.expirationDate,
    agencyPercentage: props.landlord.agencyPercentage,
});

const showModal = ref(false);
const updatedLandlordName = ref('');

const submit = () => {
    form.put(route('landlords.update', props.landlord.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            updatedLandlordName.value = `${form.firstName} ${form.lastName}`;
            showModal.value = true;
        },
        onError: (errors) => {
            console.error('Errors:', errors);
        },
    });
};

const cancel = () => {
    form.reset();
};

const closeModal = () => {
    showModal.value = false;
};
</script>
