<template>
    <div class="relative w-full h-6">
        <div class="absolute w-full h-2 bg-gray-200 rounded-full top-1/2 transform -translate-y-1/2"></div>
        <div class="absolute h-2 bg-indigo-600 rounded-full top-1/2 transform -translate-y-1/2"
            :style="{ width: `${percentage}%` }"></div>
        <input type="range" :min="min" :max="max" :step="step" :value="modelValue" @input="updateValue"
            class="absolute w-full h-6 opacity-0 cursor-pointer" />
        <div class="absolute w-6 h-6 bg-white border-2 border-indigo-600 rounded-full top-1/2 transform -translate-x-1/2 -translate-y-1/2"
            :style="{ left: `${percentage}%` }"></div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Number,
        required: true
    },
    min: {
        type: Number,
        default: 0
    },
    max: {
        type: Number,
        default: 100
    },
    step: {
        type: Number,
        default: 1
    }
});

const emit = defineEmits(['update:modelValue']);

const percentage = computed(() => {
    return ((props.modelValue - props.min) / (props.max - props.min)) * 100;
});

const updateValue = (event) => {
    emit('update:modelValue', Number(event.target.value));
};
</script>
