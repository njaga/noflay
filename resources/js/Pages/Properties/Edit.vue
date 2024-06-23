<template>
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Modifier une propriété</h1>
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                    Type de propriété
                </label>
                <select v-model="property.type" id="type" name="type" class="form-select mt-1 block w-full">
                    <option value="appartement">Appartement</option>
                    <option value="studio">Studio</option>
                    <option value="villa">Villa</option>
                    <option value="terrain">Terrain</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                    Adresse
                </label>
                <input v-model="property.address" id="address" name="address" type="text" class="form-input mt-1 block w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="size">
                    Taille (m²)
                </label>
                <input v-model="property.size" id="size" name="size" type="number" class="form-input mt-1 block w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="rooms">
                    Nombre de pièces
                </label>
                <input v-model="property.rooms" id="rooms" name="rooms" type="number" class="form-input mt-1 block w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                    Prix (FCFA)
                </label>
                <input v-model="property.price" id="price" name="price" type="number" class="form-input mt-1 block w-full" />
            </div>
            <div class="flex items-center justify-between">
                <button @click="submitForm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

export default {
    setup() {
        const property = ref({
            type: '',
            address: '',
            size: null,
            rooms: null,
            price: null
        });

        const { props } = usePage();

        onMounted(() => {
            property.value = props.value.property;
        });

        const submitForm = () => {
            const form = useForm(property.value);
            form.put(`/properties/${property.value.id}`);
        };

        return {
            property,
            submitForm
        };
    }
};
</script>

<style scoped>
/* Ajouter des styles personnalisés ici */
</style>
