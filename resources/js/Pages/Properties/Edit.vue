<template>
    <AppLayout title="Modifier une propriété">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <h1
                        class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-8 animate-gradient">
                        Modifier une propriété
                    </h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-medium text-gray-700">Nom de la propriété</label>
                                <input id="name" v-model="form.name" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="property_type" class="text-sm font-medium text-gray-700">Type de
                                    propriété</label>
                                <div class="flex items-center space-x-2">
                                    <select id="property_type" v-model="form.property_type" required
                                        class="flex-grow px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                        <option value="">Sélectionnez un type</option>
                                        <option v-for="type in propertyTypes" :key="type" :value="type">{{ type }}
                                        </option>
                                    </select>
                                    <button @click.prevent="showNewTypeModal = true" type="button"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 ease-in-out">
                                        +
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2 sm:col-span-2">
                                <label for="description" class="text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" v-model="form.description"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"></textarea>
                            </div>

                            <div class="space-y-2">
                                <label for="address" class="text-sm font-medium text-gray-700">Adresse</label>
                                <input id="address" v-model="form.address" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="available_count" class="text-sm font-medium text-gray-700">Nombre
                                    disponible</label>
                                <input id="available_count" v-model="form.available_count" type="number" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="price" class="text-sm font-medium text-gray-700">Prix</label>
                                <input id="price" v-model="form.price" type="number" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div v-if="auth.isSuperAdmin" class="space-y-2">
                                <label for="company_id" class="text-sm font-medium text-gray-700">Entreprise</label>
                                <select id="company_id" v-model="form.company_id" @change="updateLandlords" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">Sélectionnez une entreprise</option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">{{
                                        company.name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="landlord_id" class="text-sm font-medium text-gray-700">Bailleur</label>
                                <select id="landlord_id" v-model="form.landlord_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">Sélectionnez un bailleur</option>
                                    <option v-for="landlord in filteredLandlords" :key="landlord.id"
                                        :value="landlord.id">{{ landlord.first_name }} {{ landlord.last_name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2 sm:col-span-2">
                                <label for="photos" class="text-sm font-medium text-gray-700">Photos</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="photos"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-indigo-300 border-dashed rounded-lg cursor-pointer bg-indigo-50 hover:bg-indigo-100 transition duration-300 ease-in-out">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-3 text-indigo-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                            <p class="mb-2 text-sm text-indigo-600"><span class="font-bold">Cliquez pour
                                                    télécharger</span></p>
                                            <p class="text-xs text-indigo-500">PNG, JPG, jusqu'à 10MB</p>
                                        </div>
                                        <input id="photos" type="file" @change="handleFileUpload" multiple
                                            class="hidden" accept="image/*" />
                                    </label>
                                </div>
                                <div v-if="previews.length"
                                    class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    <div v-for="(preview, index) in previews" :key="index" class="relative">
                                        <img :src="preview" class="w-full h-32 object-cover rounded-lg shadow-md" />
                                        <button @click="removePreview(index)"
                                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 transition duration-200">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <p v-if="form.photos.length >= 5" class="mt-2 text-sm text-red-600">Vous pouvez
                                    télécharger jusqu'à 5 photos.</p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="form.processing || form.photos.length > 5"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        class="bi bi-floppy h-5 w-5 mr-2" viewBox="0 0 16 16">
                                        <path d="M11 2H9v3h2z" />
                                        <path
                                            d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                                    </svg>
                                    Enregistrer
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal pour ajouter un nouveau type de propriété -->
        <div v-if="showNewTypeModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
            <div class="bg-white p-5 rounded-lg shadow-xl">
                <h2 class="text-lg font-bold mb-4">Ajouter un nouveau type de propriété</h2>
                <input v-model="newPropertyType" type="text" class="w-full px-3 py-2 border rounded-lg mb-4"
                    placeholder="Entrez le nouveau type">
                <div class="flex justify-end">
                    <button @click="addNewPropertyType"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg mr-2">Ajouter</button>
                    <button @click="showNewTypeModal = false" class="px-4 py-2 bg-gray-300 rounded-lg">Annuler</button>
                </div>
            </div>
        </div>

        <!-- Modal de succès -->
        <Modal :show="showSuccessModal" @close="closeSuccessModal">
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 text-green-500 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500">Propriété mise à jour avec succès</h3>
                <button @click="closeSuccessModal" type="button"
                    class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    OK
                </button>
            </div>
        </Modal>

        <!-- Modal d'erreur -->
        <Modal :show="showErrorModal" @close="closeErrorModal">
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 text-red-500 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500">Une erreur est survenue lors de la mise à jour</h3>
                <p class="mb-5 text-sm text-gray-500">{{ errorMessage }}</p>
                <button @click="closeErrorModal" type="button"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Fermer
                </button>
            </div>
        </Modal>

    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

const { auth, companies, landlords: allLandlords, property, propertyTypes: initialPropertyTypes } = usePage().props;

if (!property) {
    throw new Error('Property is not defined');
}

const propertyTypes = ref(initialPropertyTypes);
const showNewTypeModal = ref(false);
const newPropertyType = ref('');
const showSuccessModal = ref(false);
const showErrorModal = ref(false);
const errorMessage = ref('');

const form = useForm({
    name: property.name,
    property_type: property.property_type,
    description: property.description,
    address: property.address,
    available_count: property.available_count,
    landlord_id: property.landlord_id,
    company_id: property.company_id,
    price: property.price,
    photos: property.photos ? JSON.parse(property.photos) : [],
});

const previews = ref(form.photos.map(photo => `/storage/${photo}`));
const filteredLandlords = ref(allLandlords);

const updateLandlords = () => {
    if (auth.isSuperAdmin && form.company_id) {
        filteredLandlords.value = allLandlords.filter(landlord => landlord.company_id === form.company_id);
    } else {
        filteredLandlords.value = allLandlords;
    }
    form.landlord_id = ''; // Reset landlord selection
};

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);

    if (form.photos.length + files.length <= 5) {
        files.forEach((file) => {
            form.photos.push(file);
            const reader = new FileReader();
            reader.onload = (e) => {
                previews.value.push(e.target.result);
            };
            reader.readAsDataURL(file);
        });
    } else {
        alert("Vous pouvez télécharger jusqu'à 5 photos.");
    }
};

const removePreview = (index) => {
    form.photos.splice(index, 1);
    previews.value.splice(index, 1);
};

const addNewPropertyType = () => {
    if (newPropertyType.value && !propertyTypes.value.includes(newPropertyType.value)) {
        propertyTypes.value.push(newPropertyType.value);
        form.property_type = newPropertyType.value;
        newPropertyType.value = '';
        showNewTypeModal.value = false;
    }
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
    // Vous pouvez ajouter ici une redirection si nécessaire
    // router.visit(route('properties.index'));
};

const closeErrorModal = () => {
    showErrorModal.value = false;
};

const submit = () => {
    const formData = new FormData();

    formData.append('name', form.name);
    formData.append('property_type', form.property_type);
    formData.append('description', form.description);
    formData.append('address', form.address);
    formData.append('available_count', form.available_count);
    formData.append('landlord_id', form.landlord_id);
    formData.append('company_id', form.company_id);
    formData.append('price', form.price);

    // Gérer les photos existantes et nouvelles
    if (form.photos && form.photos.length > 0) {
        const existingPhotos = form.photos.filter(photo => typeof photo === 'string');
        formData.append('existing_photos', JSON.stringify(existingPhotos));

        form.photos.forEach((photo, index) => {
            if (photo instanceof File) {
                formData.append(`photos[${index}]`, photo, photo.name);
            }
        });
    }

    formData.append('_method', 'PUT'); // Pour simuler une requête PUT

    axios.post(route('properties.update', property.id), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        }
    })
    .then(response => {
        showSuccessModal.value = true;
    })
    .catch(error => {
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            errorMessage.value = Object.values(errors).flat().join('\n');
        } else {
            errorMessage.value = 'Une erreur inattendue est survenue. Veuillez réessayer.';
        }
        showErrorModal.value = true;
    });
};

// Initialize filtered landlords
updateLandlords();
</script>

<style scoped>
@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 5s ease infinite;
}
</style>
