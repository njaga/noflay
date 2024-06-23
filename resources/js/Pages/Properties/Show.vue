<template>
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Détails de la propriété</h1>
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <p><strong>Type :</strong> {{ property.type }}</p>
            <p><strong>Adresse :</strong> {{ property.address }}</p>
            <p><strong>Taille :</strong> {{ property.size }} m²</p>
            <p><strong>Nombre de pièces :</strong> {{ property.rooms }}</p>
            <p><strong>Prix :</strong> {{ property.price }} FCFA</p>
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/AppLayout.vue';

export default {
    setup() {
        const property = ref({});
        const { props } = usePage();

        onMounted(() => {
            property.value = props.value.property;
        });

        return {
            property
        };
    }
};
</script>

<style scoped>
/* Ajouter des styles personnalisés ici */
</style>
