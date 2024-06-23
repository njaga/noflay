<!-- SidebarDropdown.vue -->
<template>
    <div>
        <div @click="toggle" class="py-2 px-4 hover:bg-indigo-700 cursor-pointer">
            <i :class="icon"></i>
            <span v-if="!isCollapsed && !isMobile" class="ml-2">{{ text }}</span>
            <i v-if="!isCollapsed && !isMobile" :class="['ml-2', isOpen ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
        </div>
        <div v-if="isOpen && !isCollapsed && !isMobile" class="pl-4">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
    icon: String,
    text: String,
    isCollapsed: Boolean,
    isMobile: Boolean
});

const isOpen = ref(false);

const toggle = () => {
    isOpen.value = !isOpen.value;
};
</script>
