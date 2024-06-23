<template>
    <AppLayout title="Créer une entreprise">
        <div class="py-12 bg-gray-100">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Créer une nouvelle entreprise</h1>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nom de l'entreprise</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-building"></i>
                                        </span>
                                        <input type="text" id="name" v-model="form.name"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                            required>
                                    </div>
                                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" id="email" v-model="form.email"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    </div>
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel" id="phone" v-model="form.phone"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    </div>
                                    <div v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <input type="text" id="address" v-model="form.address"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    </div>
                                    <div v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="website" class="block text-sm font-medium text-gray-700">Site web</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-globe"></i>
                                        </span>
                                        <input type="text" id="website" v-model="form.website"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                            placeholder="www.example.com">
                                    </div>
                                    <div v-if="form.errors.website" class="text-red-500 text-sm mt-1">{{ form.errors.website }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-user-tag"></i>
                                        </span>
                                        <select id="role" v-model="form.role"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                            <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
                                        </select>
                                    </div>
                                    <div v-if="form.errors.role" class="text-red-500 text-sm mt-1">{{ form.errors.role }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="is_active" v-model="form.is_active"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_active" class="font-medium text-gray-700">Entreprise active</label>
                                            <p class="text-gray-500">Cochez cette case si l'entreprise est actuellement active.</p>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.is_active" class="text-red-500 text-sm mt-1">{{ form.errors.is_active }}</div>
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
                                    {{ form.processing ? 'Création en cours...' : 'Créer l\'entreprise' }}
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
                    Entreprise créée avec succès
                </h2>
                <p class="text-sm text-gray-600 mb-4">
                    L'entreprise "{{ createdCompanyName }}" a été ajoutée à la base de données.
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
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

const { props } = usePage();
const roles = props.roles;

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    website: '',
    role: '',
    is_active: true,
});

const showModal = ref(false);
const createdCompanyName = ref('');

const submit = () => {
    form.post(route('companies.store'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            createdCompanyName.value = form.name;
            showModal.value = true;
            form.reset();
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
