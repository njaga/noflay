<template>
  <div class="feature-item" :class="{ 'text-right': align === 'right' }">
    <div class="flex items-center" :class="{ 'flex-row-reverse': align === 'right' }">
      <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 text-indigo-500 rounded-full flex items-center justify-center" :class="{ 'ml-4': align === 'right', 'mr-4': align === 'left' }">
        <i :class="['bi', feature.icon, 'text-2xl']"></i>
      </div>
      <div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">
          {{ feature.title }}
        </h3>
        <p class="text-gray-600">
          {{ feature.description }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  feature: {
    type: Object,
    required: true
  },
  align: {
    type: String,
    default: 'left'
  }
});
</script>

<style scoped>
.feature-item {
  transition: all 0.5s ease;
}
</style>
