<template>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800">{{ title }}</h2>
            <p class="text-gray-600 mt-2">{{ email }}</p>

            <div class="mt-4 flex justify-between items-center">
                <div>
                    <Link :href="viewUrl" class="text-indigo-600 hover:text-indigo-800 mr-4">
                        <i class="fas fa-eye"></i> Voir
                    </Link>
                    <Link :href="editUrl" class="text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-edit"></i> Modifier
                    </Link>
                </div>
                <button @click="$emit('delete')" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </div>

            <div v-if="canToggleStatus" class="mt-4">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" :checked="isActive" class="sr-only peer" @change="toggleStatus">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ isActive ? 'Actif' : 'Inactif' }}</span>
                </label>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: String,
    email: String,
    viewUrl: String,
    editUrl: String,
    isActive: Boolean,
    canToggleStatus: Boolean,
});

const emit = defineEmits(['delete', 'toggleStatus']);

const toggleStatus = () => {
    emit('toggleStatus');
};
</script>
