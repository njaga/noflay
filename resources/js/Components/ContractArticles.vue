<template>
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-file-alt mr-2 text-indigo-600"></i> Articles du Contrat
        </h2>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div v-for="article in articles" :key="article.title" class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ article.title }}</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ article.content }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    articles: Array,
});
</script>
