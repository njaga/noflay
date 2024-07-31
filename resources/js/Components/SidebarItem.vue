<!-- SidebarItem.vue -->
<template>
    <Link
        :href="href"
        class="flex items-center py-2 px-4 hover:bg-indigo-700 cursor-pointer transition-colors duration-200"
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
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    icon: String,
    text: String,
    href: String,
    isCollapsed: Boolean,
    isMobile: Boolean,
    isActive: Boolean
});
</script>
