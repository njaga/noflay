<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 class="text-2xl font-bold mb-4">Confirmer la suppression</h3>
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
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
