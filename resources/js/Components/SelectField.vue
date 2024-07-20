<template>
    <div class="mb-4">
        <label :for="id" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select
                :id="id"
                :value="modelValue"
                @change="$emit('update:modelValue', $event.target.value)"
                :required="required"
                class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out appearance-none"
            >
                <option value="">Sélectionnez une entreprise</option>
                <option v-for="company in companies" :key="company.id" :value="company.id">
                    {{ company.name }}
                </option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <i class="fas fa-chevron-down text-gray-400"></i>
            </div>
        </div>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    label: {
        type: String,
        default: 'Entreprise',
    },
    required: {
        type: Boolean,
        default: false,
    },
    companies: {
        type: Array,
        required: true,
    },
    error: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const id = computed(() => `company-select-${props.label.toLowerCase().replace(/\s+/g, '-')}`);
</script>
