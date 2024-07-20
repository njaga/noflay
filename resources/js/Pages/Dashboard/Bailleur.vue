<template>
    <AppLayout title="Tableau de bord - Bailleur">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-semibold mb-6">Bienvenue, Bailleur</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="p-6 bg-yellow-100 rounded-lg">
                                <h3 class="text-xl font-semibold">Propriétés</h3>
                                <p class="text-lg">{{ properties.length }}</p>
                            </div>
                            <div class="p-6 bg-green-100 rounded-lg">
                                <h3 class="text-xl font-semibold">Contrats</h3>
                                <p class="text-lg">{{ contracts.length }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { props } = usePage();
const properties = ref(props.properties || []);
const contracts = ref(props.contracts || []);
</script>
