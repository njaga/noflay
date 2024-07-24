<template>
    <form @submit.prevent="handleSearch" class="w-full max-w-lg">
        <div class="relative">
            <input
                type="search"
                class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent"
                placeholder="Rechercher..."
                v-model="searchTerm"
            />
            <button
                type="submit"
                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-transparent rounded-r-full hover:text-indigo-600"
            >
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'; // Importez le router Inertia.js

const searchTerm = ref('');

const handleSearch = () => {
    if (searchTerm.value.trim()) {
        router.visit(route('search', { q: searchTerm.value })); // Utilisez Inertia.js pour naviguer
    }
};
</script>
