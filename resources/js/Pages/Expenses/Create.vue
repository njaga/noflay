<template>
    <AppLayout title="Ajouter une Dépense">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-3xl font-bold mb-8 text-gray-800">Ajouter une Dépense</h2>
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="property_id" class="block text-sm font-medium text-gray-700">Propriété</label>
                                    <select id="property_id" v-model="form.property_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="" disabled>Sélectionnez une propriété</option>
                                        <option v-for="property in properties" :key="property.id" :value="property.id">
                                            {{ property.name }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <input id="description" v-model="form.description" type="text" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required />
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
                                    <input id="amount" v-model="form.amount" type="number" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required />
                                </div>
                                <div>
                                    <label for="expense_date" class="block text-sm font-medium text-gray-700">Date de Dépense</label>
                                    <input id="expense_date" v-model="form.expense_date" type="date" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required />
                                </div>
                            </div>
                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="btn-primary">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
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

const form = ref({
    property_id: '',
    description: '',
    amount: '',
    expense_date: '',
});

const submit = () => {
    router.post(route('expenses.store'), form.value);
};
</script>
