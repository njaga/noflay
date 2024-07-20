<template>
    <button
      :class="[
        'px-4 py-2 rounded-md transition-colors duration-200',
        'focus:outline-none focus:ring-2 focus:ring-offset-2',
        variantClasses,
        sizeClasses,
        { 'opacity-50 cursor-not-allowed': disabled }
      ]"
      :disabled="disabled"
      @click="$emit('click')"
    >
      <slot></slot>
    </button>
  </template>

  <script setup>
  import { computed } from 'vue';

  const props = defineProps({
    variant: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'primary', 'secondary', 'danger', 'outline'].includes(value)
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    disabled: {
      type: Boolean,
      default: false
    }
  });

  const variantClasses = computed(() => {
    const classes = {
      default: 'bg-gray-200 text-gray-800 hover:bg-gray-300',
      primary: 'bg-indigo-600 text-white hover:bg-indigo-700',
      secondary: 'bg-purple-600 text-white hover:bg-purple-700',
      danger: 'bg-red-600 text-white hover:bg-red-700',
      outline: 'bg-transparent border border-gray-300 text-gray-700 hover:bg-gray-100'
    };
    return classes[props.variant];
  });

  const sizeClasses = computed(() => {
    const classes = {
      sm: 'text-sm',
      md: 'text-base',
      lg: 'text-lg'
    };
    return classes[props.size];
  });
  </script>
