<template>
    <div>
      <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
      <input
        :id="id"
        type="file"
        @change="handleFileChange"
        class="mt-1 block w-full text-sm text-gray-500
          file:mr-4 file:py-2 file:px-4
          file:rounded-md file:border-0
          file:text-sm file:font-semibold
          file:bg-indigo-50 file:text-indigo-700
          hover:file:bg-indigo-100"
        :required="required"
        :multiple="multiple"
      >
      <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
    </div>
  </template>

  <script setup>


  const props = defineProps({
    modelValue: Array,
    label: String,
    id: {
      type: String,
      default: () => `file-upload-${Math.random().toString(36).substr(2, 9)}`
    },
    required: Boolean,
    multiple: Boolean,
    error: String
  });

  const emit =  (['update:modelValue']);

  const handleFileChange = (event) => {
    const files = Array.from(event.target.files);
    emit('update:modelValue', files);
  };
  </script>
