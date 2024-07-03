<template>
    <input :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder" :class="[
            'w-full px-3 py-2 border rounded-md transition-colors duration-200',
            'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500',
            { 'border-red-500': error }
        ]" />
</template>

<script setup>
defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    type: {
        type: String,
        default: 'text'
    },
    placeholder: {
        type: String,
        default: ''
    },
    error: {
        type: Boolean,
        default: false
    }
});

defineEmits(['update:modelValue']);
</script>
