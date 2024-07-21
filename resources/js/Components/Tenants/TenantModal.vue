<template>
    <Modal :show="show" @close="close">
        <div class="p-6 bg-white rounded-lg shadow-xl">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
            <p class="text-sm text-gray-500 mb-4">
                Êtes-vous sûr de vouloir supprimer le locataire {{ tenant?.first_name }} {{ tenant?.last_name }} ? Cette action est irréversible.
            </p>
            <div class="mt-6 flex justify-end space-x-3">
                <button @click="close" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">Annuler</button>
                <button @click="confirm" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">Supprimer</button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from '@/Components/Modal.vue';
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    show: Boolean,
    tenant: Object,
});

const emit = defineEmits(['close', 'confirm']);

const close = () => {
    emit('close');
};

const confirm = () => {
    emit('confirm');
};
</script>
