<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="bg-white px-6 pt-6 pb-4 rounded-t-2xl shadow-lg sm:p-8 sm:pb-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>

                <div class="mt-4 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
                    <div class="mt-2 text-sm text-gray-600">{{ message }}</div>
                </div>
            </div>
        </div>

        <div class="flex justify-end px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button @click="close" class="btn-secondary mr-2">Annuler</button>
            <button @click="confirm" class="btn-danger">Confirmer</button>
        </div>
    </Modal>
</template>

<script setup>
import { defineProps } from 'vue';
import Modal from './Modal.vue';

const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit =  defineEmits(['close', 'confirm']);

const close = () => {
    console.log('NewConfirmationModal close method called');
    emit('close');
};

const confirm = () => {
    console.log('NewConfirmationModal confirm method called');
    emit('confirm');
};
</script>

<style scoped>
.btn-secondary {
    @apply bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75;
}

.btn-danger {
    @apply bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-75;
}

.modal-bg {
    @apply fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-75 backdrop-blur-sm transition-opacity duration-300 ease-in-out;
}
</style>
