<template>
    <NewModal :show="show" @close="handleClose">
        <template #title>
            Modifier la Transaction
        </template>
        <template #content>
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" v-model="transaction.date" id="date" class="input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out">
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
                    <input type="number" v-model="transaction.amount" id="amount" class="input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out">
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" v-model="transaction.type" id="type" class="input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="transaction.description" id="description" class="input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out"></textarea>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="handleClose" class="btn-secondary mr-3">Annuler</button>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </template>
    </NewModal>
</template>

<script setup>
import { ref, watch } from 'vue';
import NewModal from '@/Components/NewModal.vue';

const props = defineProps({
    show: Boolean,
    transaction: Object,
});
const emit = defineEmits(['close', 'save']);

const transaction = ref({ ...props.transaction });

watch(() => props.show, () => {
    if (props.show) {
        transaction.value = { ...props.transaction };
    }
});

function handleClose() {
    emit('close');
}

function submit() {
    emit('save', transaction.value);
    handleClose();
}
</script>

<style scoped>
.input {
    @apply mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out;
}

.btn-secondary {
    @apply bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 transition duration-150 ease-in-out;
}

.btn-primary {
    @apply bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75 transition duration-150 ease-in-out;
}

.modal-bg {
    @apply fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-75 backdrop-blur-sm transition-opacity duration-300 ease-in-out;
}
</style>
