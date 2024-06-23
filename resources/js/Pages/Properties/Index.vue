<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from '@/Layouts/AppLayout.vue';

defineProps({
    properties: Array,
});
</script>

<template>
    <Layout>
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold mb-4">Liste des propriétés</h1>
            <Link
                :href="route('properties.create')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block"
            >
                Créer une nouvelle propriété
            </Link>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Nom</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Adresse</th>
                        <th class="py-2">Prix (FCFA)</th>
                        <th class="py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="property in properties" :key="property.id">
                        <td class="py-2">{{ property.name }}</td>
                        <td class="py-2">{{ property.type }}</td>
                        <td class="py-2">{{ property.address }}</td>
                        <td class="py-2">{{ property.price }}</td>
                        <td class="py-2">
                            <Link :href="route('properties.edit', property.id)" class="text-blue-600 hover:text-blue-800 mr-2">Editer</Link>
                            <Link :href="route('properties.show', property.id)" class="text-blue-600 hover:text-blue-800">Voir</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Layout>
</template>
