<template>
    <div class="p-6 bg-gray-50 border-b border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4">
                <select
                    v-model="filters.landlord"
                    @change="applyFilters"
                    class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">Tous les bailleurs</option>
                    <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
                        {{ landlord.first_name }} {{ landlord.last_name }}
                    </option>
                </select>
                <select
                    v-model="filters.dateRange"
                    @change="applyFilters"
                    class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="all">Toutes les dates</option>
                    <option value="thisMonth">Ce mois</option>
                    <option value="lastMonth">Mois dernier</option>
                    <option value="thisYear">Cette année</option>
                </select>
            </div>
            <div class="flex items-center space-x-4">
                <button
                    @click="downloadPayoutsList"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                >
                    <i class="bi bi-download mr-2"></i>Télécharger
                </button>
                <div class="flex border rounded-md">
                    <button
                        @click="setViewMode('grid')"
                        :class="{ 'bg-indigo-600 text-white': viewMode === 'grid' }"
                        class="px-4 py-2 rounded-l-md"
                    >
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>
                    <button
                        @click="setViewMode('list')"
                        :class="{ 'bg-indigo-600 text-white': viewMode === 'list' }"
                        class="px-4 py-2 rounded-r-md"
                    >
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps(['filters', 'landlords', 'viewMode']);
const emits = defineEmits(['update:filters', 'update:viewMode', 'downloadPayoutsList']);

const applyFilters = () => {
    emits('update:filters', { ...filters });
};

const setViewMode = (mode) => {
    emits('update:viewMode', mode);
};
</script>
