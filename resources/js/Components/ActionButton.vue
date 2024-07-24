<template>
    <component
        :is="href ? 'Link' : 'button'"
        :href="href"
        :class="[
            'inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-sm shadow-sm transition-all duration-200 ease-in-out',
            'focus:outline-none focus:ring-2 focus:ring-offset-2',
            size === 'sm'
                ? 'text-xs'
                : size === 'lg'
                ? 'text-base px-6 py-3'
                : 'text-sm',
            variant === 'outline' ? 'border' : '',
            disabled
                ? 'opacity-50 cursor-not-allowed'
                : 'transform hover:-translate-y-1',
            className,
        ]"
        :disabled="disabled"
        @click="$emit('click')"
    >
        <i v-if="icon" :class="['mr-2', icon]"></i>
        {{ text }}
        <slot></slot>
    </component>
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        default: null,
    },
    icon: {
        type: String,
        default: null,
    },
    text: {
        type: String,
        default: "",
    },
    variant: {
        type: String,
        default: "solid",
        validator: (value) => ["solid", "outline"].includes(value),
    },
    size: {
        type: String,
        default: "md",
        validator: (value) => ["sm", "md", "lg"].includes(value),
    },
    className: {
        type: String,
        default:
            "bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

["click"];
</script>

<style scoped>
@import "bootstrap-icons/font/bootstrap-icons.css";
</style>
