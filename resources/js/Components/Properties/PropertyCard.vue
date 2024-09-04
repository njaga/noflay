<template>
    <div
        class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="relative">
            <img :src="getPropertyImage(property)" :alt="property.name" class="w-full h-48 object-cover" />
            <div class="absolute top-0 left-0 bg-indigo-600 text-white text-xs uppercase px-3 py-1 rounded-br-lg">
                {{ property.property_type }}
            </div>
            <div class="absolute bottom-0 right-0 text-white text-xs px-3 py-1 rounded-tl-lg"
                :class="property.available_count > 0 ? 'bg-green-500' : 'bg-red-500'">
                {{ property.available_count > 0 ? 'Disponible' : 'Indisponible' }}
            </div>
        </div>
        <div class="p-5">
            <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ property.name }}</h3>
            <p class="text-sm text-gray-600 mb-4">{{ property.description }}</p>
            <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                <span><i class="fas fa-map-marker-alt mr-1"></i> {{ property.address }}</span>
            </div>
            <div class="text-2xl font-bold text-indigo-600 mb-4">
                {{ formatPrice(property.price) }} CFA
            </div>
            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <button v-if="canUpdate(property)" @click="editProperty(property)" class="btn-icon">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button @click="viewPropertyDetails(property)" class="btn-icon">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button v-if="canUpdate(property)" @click="togglePropertyStatus(property)" class="btn-icon">
                        <i :class="['fas', property.available_count > 0 ? 'fa-toggle-on' : 'fa-toggle-off']"></i>
                    </button>
                    <button v-if="canDelete(property)" @click="openDeleteModal(property)" class="btn-icon text-red-600">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { router } from '@inertiajs/vue3'; // Utilisation directe de router

const props = defineProps({
    property: Object,
    auth: Object
});

const emit = defineEmits(['openDeleteModal', 'togglePropertyStatus', 'close', 'confirm']);

const viewPropertyDetails = (property) => {
    router.get(route('properties.show', property.id));
};

const editProperty = (property) => {
    router.get(route('properties.edit', property.id));
};

const openDeleteModal = (property) => {
    emit('openDeleteModal', property);
};

const togglePropertyStatus = (property) => {
    emit('togglePropertyStatus', property);
};

const getPropertyImage = (property) => {
    if (property.photos && property.photos.length) {
        const parsedPhotos = JSON.parse(property.photos);
        if (parsedPhotos.length > 0) {
            const photoPath = parsedPhotos[0].replace(/^public\//, '');
            return `/storage/${photoPath}`;
        }
    }
    return '/img/default-property.jpg'; // Image par défaut
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR').format(price);
};

const canUpdate = (property) => {
    const userRoles = props.auth.roles;
    const user = props.auth.user;
    return (
        userRoles.includes('super_admin') ||
        (userRoles.includes('admin_entreprise') && user.company_id === property.company_id)
    );
};

const canDelete = (property) => {
    return canUpdate(property);
};
</script>

<style scoped>
.btn-icon {
    @apply p-2 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150;
}
</style>
