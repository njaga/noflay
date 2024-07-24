<template>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-indigo-200">
        <div :class="headerClass">
            <i :class="iconClass"></i>
            <h2 class="text-lg font-semibold text-white">{{ title }}</h2>
        </div>
        <ul class="p-4 sm:p-6">
            <li v-for="item in items" :key="item.id" class="text-indigo-900 py-2 border-b border-indigo-100 flex justify-between">
                <span>
                    <i class="bi bi-envelope-fill mr-2 text-indigo-500"></i>
                    <template v-for="(field, index) in fields" :key="index">
                        <span v-if="!formatters[field]">{{ item[field] }}</span>
                        <span v-else>{{ formatters[field](item[field]) }}</span>
                        <span v-if="index < fields.length - 1"> - </span>
                    </template>
                </span>
                <Link :href="route(routeName, item.id)" class="text-indigo-600 hover:text-indigo-900">
                    Voir plus
                </Link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    icon: String,
    title: String,
    items: Array,
    fields: Array,
    routeName: String,
    formatters: {
        type: Object,
        default: () => ({})
    }
});

const headerClass = 'px-4 py-3 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-400 to-indigo-500 flex items-center';
const iconClass = `${props.icon} text-white mr-2`;
</script>
