<template>
    <div class="flex items-center space-x-2">
        <i :class="`fas fa-${icon} text-blue-500`"></i>
        <div>
            <div class="text-gray-600 text-sm">{{ label }}</div>
            <div class="text-gray-900 font-semibold">{{ value }}</div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    icon: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    value: {
        type: String,
        required: true
    }
});
</script>
