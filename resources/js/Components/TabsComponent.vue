<template>
  <div>
    <div class="border-b border-gray-200">
      <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <button
          v-for="tab in tabs"
          :key="tab.name"
          @click="selectTab(tab.name)"
          :class="[
            tab.name === modelValue
              ? 'border-indigo-500 text-indigo-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
          ]"
        >
          {{ tab.label }}
        </button>
      </nav>
    </div>
    <div class="mt-4">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import { provide } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['update:modelValue']);

const tabs = [];

const addTab = (tab) => {
  tabs.push(tab);
};

const selectTab = (tabName) => {
  emit('update:modelValue', tabName);
};

provide('tabs-component', {
  addTab,
  selectedTab: props.modelValue
});
</script>
