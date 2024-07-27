<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 sm:p-10">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="bi bi-exclamation-circle-fill text-red-500 mr-3"></i>
                Confirmer la suppression
            </h3>
            <p class="mb-4 text-gray-600">
                Êtes-vous sûr de vouloir supprimer ce versement ? Cette action est irréversible.
            </p>
            <div class="flex justify-end space-x-4">
                <button @click="$emit('close')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow-sm hover:bg-gray-400">
                    Annuler
                </button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-700">
                    Supprimer
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>

import Modal from "@/Components/Modal.vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    payoutId: {
        type: Number,
        required: true,
    },
});

const emit =  (['close', 'confirm']);

const confirmDelete = () => {
    emit('confirm', props.payoutId);
};
</script>
