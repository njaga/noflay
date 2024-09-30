<!-- SidebarDropdown.vue -->
<template>
    <div>
        <div
            @click="handleClick"
            class="py-2 px-4 cursor-pointer transition-colors duration-200"
            :class="[
                isActive
                    ? 'bg-white !text-indigo-800 hover:bg-white'
                    : 'text-white hover:bg-indigo-700'
            ]"
            :style="isActive ? 'background-color: white !important;' : ''"
        >
            <i :class="[icon, isActive ? 'text-indigo-800' : 'text-white']"></i>
            <span
                v-if="!isCollapsed && !isMobile"
                class="ml-2"
                :class="isActive ? 'text-indigo-800' : 'text-white'"
            >
                {{ text }}
            </span>
            <i
                v-if="!isCollapsed && !isMobile"
                :class="[
                    'ml-2',
                    isOpen ? 'bi-chevron-up' : 'bi-chevron-down',
                    isActive ? 'text-indigo-800' : 'text-white'
                ]"
            ></i>
        </div>
        <div v-if="isOpen && !isCollapsed && !isMobile" class="pl-4">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    icon: String,
    text: String,
    isCollapsed: Boolean,
    isMobile: Boolean,
    defaultHref: String,
    isActive: Boolean
});

const isOpen = ref(false);

const handleClick = () => {
    if (props.isCollapsed || props.isMobile) {
        router.visit(props.defaultHref);
    } else {
        isOpen.value = !isOpen.value;
    }
};
</script>
