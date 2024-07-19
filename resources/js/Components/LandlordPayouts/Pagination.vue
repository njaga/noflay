<!-- Components/LandlordPayouts/Pagination.vue -->
<template>
    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <button @click="$emit('change-page', currentPage - 1)" :disabled="currentPage === 1"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
            </button>
            <button @click="$emit('change-page', currentPage + 1)" :disabled="currentPage === totalPages"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Next
            </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                    to
                    <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, totalItems) }}</span>
                    of
                    <span class="font-medium">{{ totalItems }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button @click="$emit('change-page', currentPage - 1)" :disabled="currentPage === 1"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button v-for="page in displayedPages" :key="page" @click="$emit('change-page', page)"
                        :class="{ 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': page === currentPage }"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        {{ page }}
                    </button>
                    <button @click="$emit('change-page', currentPage + 1)" :disabled="currentPage === totalPages"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    totalPages: {
        type: Number,
        required: true
    },
    totalItems: {
        type: Number,
        required: true
    },
    itemsPerPage: {
        type: Number,
        required: true
    }
});

defineEmits(['change-page']);

const displayedPages = computed(() => {
    const range = 2;
    let start = Math.max(1, props.currentPage - range);
    let end = Math.min(props.totalPages, props.currentPage + range);

    if (end - start + 1 < range * 2 + 1) {
        if (start === 1) {
            end = Math.min(start + range * 2, props.totalPages);
        } else {
            start = Math.max(end - range * 2, 1);
        }
    }

    return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});
</script>
