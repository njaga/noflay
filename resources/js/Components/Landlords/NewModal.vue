<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-75 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-2xl shadow-2xl transform transition-all sm:w-full sm:max-w-lg">
            <div class="bg-white p-6 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800" id="modal-title">
                        <slot name="title"></slot>
                    </h3>
                    <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition duration-200 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="mt-4">
                    <slot name="content"></slot>
                </div>
            </div>
            <div class="bg-gray-50 p-4 rounded-b-2xl flex justify-end space-x-4">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>


const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
});

const emit =  defineEmits(['close']);
</script>

<style scoped>
/* Ajout de transitions pour les effets visuels */
.bg-opacity-75 {
    transition: background-color 0.3s ease-in-out;
}

.fixed {
    transition: opacity 0.3s ease-in-out;
}
</style>
