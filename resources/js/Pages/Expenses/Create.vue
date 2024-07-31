<template>
    <AppLayout title="Ajouter une dépense">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-8 animate-gradient">
                        Ajouter une dépense
                    </h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="property_id" class="text-sm font-medium text-gray-700">Propriété</label>
                                <select id="property_id" v-model="form.property_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">Sélectionnez une propriété</option>
                                    <option v-for="property in properties" :key="property.id" :value="property.id">
                                        {{ property.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.property_id" class="text-red-500 text-xs mt-1">{{ form.errors.property_id }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="type" class="text-sm font-medium text-gray-700">Type de dépense</label>
                                <select id="type" v-model="form.type" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">Sélectionnez un type</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="frais judiciaires">Frais judiciaires</option>
                                    <option value="taxes">Taxes</option>
                                    <option value="Assurance">Assurance</option>
                                    <option value="other">Autre</option>
                                </select>
                                <div v-if="form.errors.type" class="text-red-500 text-xs mt-1">{{ form.errors.type }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="description" class="text-sm font-medium text-gray-700">Description</label>
                                <input id="description" v-model="form.description" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="amount" class="text-sm font-medium text-gray-700">Montant</label>
                                <input id="amount" v-model="form.amount" type="number" step="0.01" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.amount" class="text-red-500 text-xs mt-1">{{ form.errors.amount }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="expense_date" class="text-sm font-medium text-gray-700">Date de dépense</label>
                                <input id="expense_date" v-model="form.expense_date" type="date" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.expense_date" class="text-red-500 text-xs mt-1">{{ form.errors.expense_date }}</div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="form.processing"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Ajouter la dépense
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de succès -->
        <Transition name="modal">
            <div v-if="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                        <div>
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Dépense ajoutée avec succès
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        La nouvelle dépense a été ajoutée à votre liste avec succès.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <button type="button" @click="showSuccessModal = false"
                                class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { properties } = usePage().props;

const form = useForm({
    property_id: '',
    type: '',
    description: '',
    amount: '',
    expense_date: '',
});

const showSuccessModal = ref(false);

const submit = () => {
    form.post(route('expenses.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            showSuccessModal.value = true;
        },
        onError: (errors) => {
            console.error(errors);
            alert('Une erreur est survenue lors de l\'ajout de la dépense');
        },
    });
};
</script>

<style scoped>
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 5s ease infinite;
}

.modal-enter-active, .modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
    opacity: 0;
}
</style>
