<template>
    <div class="relative">
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600 peer"
            :class="{ 'border-red-500': error }"
            placeholder=" "
        />
        <label
            :for="id"
            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1"
        >
            {{ label }}
        </label>
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i :class="[icon, error ? 'text-red-500' : 'text-gray-400']"></i>
        </div>
        <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
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
    icon: String,
    error: String
});
defineEmits(['update:modelValue']);
</script>
