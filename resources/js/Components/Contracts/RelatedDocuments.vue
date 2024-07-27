<template>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Documents liés</h3>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-200">
                <li v-for="doc in relatedDocuments" :key="doc.id" class="py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <i :class="['text-gray-400 mr-3', doc.icon]"></i>
                        <span class="text-sm font-medium text-gray-900">{{ doc.name }}</span>
                    </div>
                    <button @click="$emit('download', doc)" class="text-sm border border-gray-300 text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md">
                        <i class="bi-download mr-2"></i> Télécharger
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
defineProps({
    relatedDocuments: Array,
});

defineEmits(['download']);
</script>
