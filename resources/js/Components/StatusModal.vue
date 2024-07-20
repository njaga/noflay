<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 class="text-2xl font-bold mb-4">Mise à jour du statut</h3>
                    <p class="mb-6">Le statut de la propriété "{{ property?.name }}" a été mis à jour avec succès.</p>
                    <div class="flex justify-end">
                        <Button @click="$emit('close')" variant="primary">OK</Button>
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

defineEmits(['close']);
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
