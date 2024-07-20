<template>
    <AppLayout :title="`Modifier le versement #${payout.id}`">
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Modifier le versement</h2>
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="landlord_id" class="block text-sm font-medium text-gray-700">Bailleur</label>
                                    <select v-model="form.landlord_id" id="landlord_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" disabled>
                                        <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
                                            {{ landlord.first_name }} {{ landlord.last_name }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
                                    <input type="number" v-model="form.amount" id="amount" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea v-model="form.description" id="description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="btn-primary">Enregistrer les modifications</button>
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
const payout = ref(props.payout);
const landlords = ref(props.landlords);

const form = ref({
    landlord_id: payout.value.landlord_id,
    amount: payout.value.amount,
    description: payout.value.description,
});

const submit = () => {
    router.put(route('landlord-payouts.update', payout.value.id), form.value, {
        onSuccess: () => router.visit(route('landlord-payouts.index'))
    });
};
</script>

<style scoped>
@import "bootstrap-icons/font/bootstrap-icons.css";
</style>
