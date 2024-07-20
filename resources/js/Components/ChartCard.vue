<template>
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ title }}</h3>
            <div class="mt-5 h-64">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true
    }
});
</script>
