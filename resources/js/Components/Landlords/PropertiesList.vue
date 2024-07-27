<template>
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-md overflow-hidden">
        <div class="p-4">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Propriétés</h3>
            <TransitionGroup name="property-list" tag="div" class="space-y-3">
                <div v-if="landlord.properties && landlord.properties.length > 0" v-for="property in landlord.properties" :key="property.id"
                    class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="p-3">
                        <h2 class="text-base font-bold text-indigo-800 mb-4 relative">
                            {{ property.name }}
                            <span class="absolute bottom-0 left-0 w-1/4 h-0.5 bg-indigo-500 rounded-full"></span>
                        </h2>
                        <p class="text-sm text-gray-600 mb-2 line-clamp-4">
                            {{ property.description }}
                        </p>
                        <div v-if="property.contracts && property.contracts.length > 0 && property.contracts[0].tenant" class="bg-indigo-50 p-2 rounded-md mb-2 text-sm">
                            <p class="font-medium text-indigo-700">
                                Locataire actuel
                            </p>
                            <p class="text-gray-800 font-semibold">
                                {{ property.contracts[0].tenant.first_name }} {{ property.contracts[0].tenant.last_name }}
                            </p>
                            <p class="text-indigo-600 font-medium">
                                Loyer: {{ formatCurrency(property.contracts[0].rent_amount) }}
                            </p>
                        </div>
                        <div v-else class="bg-yellow-50 p-2 rounded-md mb-2 text-sm">
                            <p class="font-medium text-yellow-700">
                                Aucun locataire actuellement
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <button @click="editProperty(property.id)"
                                class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                                <i class="bi bi-pencil-square mr-1"></i> Éditer
                            </button>
                            <button @click="confirmDeleteProperty(property.id)"
                                class="text-sm text-red-500 hover:text-red-700 transition-colors duration-200">
                                <i class="bi bi-trash mr-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center p-10">
                    <i class="bi bi-house-door text-6xl text-indigo-600 mb-3"></i>
                    <h4 class="text-xl font-semibold">Aucune propriété trouvée</h4>
                </div>
            </TransitionGroup>
            <button @click="addNewProperty"
                class="mt-4 bg-indigo-600 text-white px-3 py-2 rounded-full text-sm hover:bg-indigo-700 transition-colors duration-200 flex items-center justify-center w-full">
                <i class="bi bi-plus-circle mr-1"></i> Ajouter une propriété
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { TransitionGroup } from "vue";

const props = defineProps({
    landlord: {
        type: Object,
        required: true,
        default: () => ({
            properties: []
        })
    }
});

const emit = defineEmits([
    "confirm-delete-property",
    "edit-property",
    "add-property",
]);

const formatCurrency = (value) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(value);
};

const confirmDeleteProperty = (propertyId) => {
    emit("confirm-delete-property", propertyId);
};

const editProperty = (propertyId) => {
    emit("edit-property", propertyId);
};

const addNewProperty = () => {
    emit("add-property");
};
</script>

<style scoped>
@import "bootstrap-icons/font/bootstrap-icons.css";

.property-list-enter-active, .property-list-leave-active {
    transition: all 0.5s ease;
}

.property-list-enter-from, .property-list-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>
