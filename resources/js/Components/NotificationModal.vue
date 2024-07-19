<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full" :class="iconClass">
                    <svg v-if="type === 'success'" class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg v-else-if="type === 'error'" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <svg v-else class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01m-6.938 4h13.856c1.054 0 1.636-1.136 1.072-2.012L13.072 4.012C12.508 3.136 11.492 3.136 10.928 4.012L2.07 18.012c-.564.876.018 2.012 1.072 2.012z" />
                    </svg>
                </div>

                <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                    <h3 class="text-lg font-medium text-gray-900">{{ title }}</h3>

                    <div class="mt-4 text-sm text-gray-600">{{ message }}</div>
                </div>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-end">
            <button @click="close" class="btn-secondary">Fermer</button>
        </div>
    </Modal>
</template>

<script setup>
import { computed } from 'vue';
import { defineProps, defineEmits } from 'vue';
import Modal from './Modal.vue';

const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
    type: String,
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

const close = () => {
    console.log('NotificationModal close method called'); // Debug log
    emit('close');
};

const iconClass = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-green-100';
        case 'error':
            return 'bg-red-100';
        default:
            return 'bg-yellow-100';
    }
});
</script>
