<template>
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Filtres avancés</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label for="filter-date" class="block text-sm font-medium text-gray-700">Date d'entrée</label>
                <input
                    type="date"
                    id="filter-date"
                    v-model="filters.date"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>
            <div>
                <label for="filter-name" class="block text-sm font-medium text-gray-700">Nom du locataire</label>
                <input
                    type="text"
                    id="filter-name"
                    v-model="filters.name"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>
            <div>
                <label for="filter-landlord" class="block text-sm font-medium text-gray-700">Bailleur</label>
                <select
                    id="filter-landlord"
                    v-model="filters.landlord"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">Tous les bailleurs</option>
                    <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
                        {{ landlord.first_name }} {{ landlord.last_name }}
                    </option>
                </select>
            </div>
            <div>
                <label for="filter-status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select
                    id="filter-status"
                    v-model="filters.status"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end space-x-3">
            <button @click="applyFilters" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                <i class="fas fa-search mr-2"></i>Rechercher
            </button>
            <button @click="resetFilters" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                <i class="fas fa-undo mr-2"></i>Réinitialiser
            </button>
        </div>
    </div>
</template>

<script setup>


const props = defineProps({
    filters: Object,
    landlords: Array
});

const emit =  defineEmits(['apply-filters', 'reset-filters']);

const applyFilters = () => {
    emit('apply-filters');
};

const resetFilters = () => {
    emit('reset-filters');
};
</script>
