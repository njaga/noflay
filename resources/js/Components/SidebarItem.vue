<!-- SidebarItem.vue -->
<template>
    <Link :href="href" class="flex items-center py-2 px-4 hover:bg-indigo-700 cursor-pointer">
        <i :class="icon"></i>
        <span v-if="!isCollapsed && !isMobile" class="ml-2">{{ text }}</span>
    </Link>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    icon: String,
    text: String,
    href: String,
    isCollapsed: Boolean,
    isMobile: Boolean
});
</script>
