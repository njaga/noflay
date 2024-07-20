<template>
    <div class="sm:col-span-2">
        <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <div class="mt-1 flex rounded-md shadow-sm">
      <span v-if="$slots.icon" class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
        <slot name="icon"></slot>
      </span>
            <input
                :id="id"
                :type="type"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-md sm:text-sm border-gray-300"
                :class="{ 'rounded-l-md': !$slots.icon }"
                :required="required"
            >
        </div>
        <div v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</div>
    </div>
</template>

<script setup>
defineProps({
    id: String,
    label: String,
    modelValue: [String, Number],
    type: {
        type: String,
        default: 'text'
    },
    error: String,
    required: Boolean
});
defineEmits(['update:modelValue']);
</script>
