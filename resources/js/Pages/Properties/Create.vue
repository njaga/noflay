<script setup>
import { useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    companies: Array,
});

const form = useForm({
    name: '',
    type: 'Appartement',
    address: '',
    price: 0,
    company_id: props.companies[0]?.id || null,
});

function submitForm() {
    form.post(route('properties.store'));
}
</script>

<template>
    <Layout>
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold mb-4">Créer une propriété</h1>
            <form @submit.prevent="submitForm" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Nom de la propriété
                    </label>
                    <input v-model="form.name" id="name" type="text" class="form-input mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                        Type de propriété
                    </label>
                    <select v-model="form.type" id="type" class="form-select mt-1 block w-full">
                        <option value="Appartement">Appartement</option>
                        <option value="Studio">Studio</option>
                        <option value="Villa">Villa</option>
                        <option value="Terraine">Terraine</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                        Adresse
                    </label>
                    <input v-model="form.address" id="address" type="text" class="form-input mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                        Prix (FCFA)
                    </label>
                    <input v-model="form.price" id="price" type="number" class="form-input mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="company_id">
                        Entreprise
                    </label>
                    <select v-model="form.company_id" id="company_id" class="form-select mt-1 block w-full" required>
                        <option v-for="company in companies" :key="company.id" :value="company.id">
                            {{ company.name }}
                        </option>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </Layout>
</template>
