<template>
    <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value)" :class="[
        'w-full px-3 py-2 border rounded-md transition-colors duration-200',
        'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500',
        { 'border-red-500': error }
    ]">
        <slot></slot>
    </select>
</template>

<script setup>
defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    error: {
        type: Boolean,
        default: false
    }
});

defineEmits(['update:modelValue']);
</script>
