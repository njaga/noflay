<template>
    <div class="flex items-center">
        <i :class="`fas fa-${icon} text-indigo-600 mr-3`"></i>
        <div>
            <h3 class="text-sm font-semibold text-gray-900">{{ label }}</h3>
            <p class="text-sm text-gray-700">{{ value }}</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    icon: String,
    label: String,
    value: String
});
</script>

<style scoped>
</style>
