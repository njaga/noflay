<template>
    <div>
        <div class="flex border-b border-gray-200">
            <button v-for="(title, index) in titles" :key="index" @click="activeTab = index" :class="[
                'py-2 px-4 font-medium text-sm focus:outline-none',
                activeTab === index
                    ? 'text-indigo-600 border-b-2 border-indigo-600'
                    : 'text-gray-500 hover:text-gray-700'
            ]">
                {{ title }}
            </button>
        </div>
        <div class="mt-4">
            <slot :name="`tab${activeTab}`"></slot>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    titles: Array,
    defaultIndex: {
        type: Number,
        default: 0
    }
});

const activeTab = ref(props.defaultIndex);
</script>
