<template>
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Ajouter une Dépense
                    </h3>
                    <div class="mt-2">
                        <input type="text" v-model="description" placeholder="Description" class="form-input mt-1 block w-full" />
                        <input type="number" v-model="amount" placeholder="Montant" class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button" class="btn-primary" @click="addExpense">
                        Ajouter
                    </button>
                    <button type="button" class="btn-secondary ml-2" @click="$emit('close')">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const description = ref('');
const amount = ref(0);

function addExpense() {
    const form = useForm({
        description: description.value,
        amount: amount.value,
    });

    form.post(route('expenses.store'), {
        onSuccess: () => {
            // Fermer le modal après avoir ajouté la dépense
            description.value = '';
            amount.value = 0;
            $emit('close');
        }
    });
}
</script>

<style>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200;
}
.btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200;
}
</style>
