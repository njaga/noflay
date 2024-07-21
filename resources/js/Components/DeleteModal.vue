<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="absolute inset-0 bg-black bg-opacity-30 backdrop-blur-sm"></div>
                <div class="bg-white rounded-lg p-8 max-w-md w-full relative z-10">
                    <div class="flex items-center mb-6">
                        <svg class="w-12 h-12 text-red-500 mr-4 animate-trash" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <h3 class="text-2xl font-bold">Confirmer la suppression</h3>
                    </div>
                    <p class="mb-6">Êtes-vous sûr de vouloir supprimer la propriété "{{ property?.name }}" ? Cette
                        action est irréversible.</p>
                    <div class="flex justify-end space-x-4">
                        <Button @click="$emit('close')" variant="outline">Annuler</Button>
                        <Button @click="$emit('confirm')" variant="danger">Supprimer</Button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import Button from '@/Components/UI/Button.vue';

defineProps({
    show: {
        type: Boolean,
        required: true
    },
    property: {
        type: Object,
        default: null
    }
});

defineEmits(['close', 'confirm']);
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s, backdrop-filter 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-active .bg-white,
.fade-leave-active .bg-white {
    transition: transform 0.3s, opacity 0.3s;
}

.fade-enter-from .bg-white,
.fade-leave-to .bg-white {
    transform: scale(0.9);
    opacity: 0;
}

@keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-5deg); }
    75% { transform: rotate(5deg); }
}

.animate-trash {
    animation: shake 0.5s ease-in-out infinite;
}
</style>
