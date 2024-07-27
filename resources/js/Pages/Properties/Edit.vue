<template>
    <AppLayout :title="`Modifier la propriété: ${property.name}`">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-100 to-purple-100">
            <div
                class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all hover:scale-105">
                <div class="p-8 sm:p-12">
                    <h1
                        class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-8 animate-gradient">
                        Modifier la propriété
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
                                        <option value="">
                                            Sélectionnez un type
                                        </option>
                                        <option v-for="type in propertyTypes" :key="type" :value="type">
                                            {{ type }}
                                        </option>
                                    </select>
                                    <button @click.prevent="showNewTypeModal = true" type="button"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 ease-in-out">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2 sm:col-span-2 mb-8">
                                <label for="description" class="text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" v-model="form.description"
                                    class="w-full h-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"></textarea>
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
                                    <option value="">
                                        Sélectionnez une entreprise
                                    </option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">
                                        {{ company.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="landlord_id" class="text-sm font-medium text-gray-700">Bailleur</label>
                                <select id="landlord_id" v-model="form.landlord_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">
                                        Sélectionnez un bailleur
                                    </option>
                                    <option v-for="landlord in filteredLandlords" :key="landlord.id"
                                        :value="landlord.id">
                                        {{ landlord.first_name }}
                                        {{ landlord.last_name }}
                                    </option>
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
                                            <p class="mb-2 text-sm text-indigo-600">
                                                <span class="font-bold">Cliquez pour modifier</span>
                                            </p>
                                            <p class="text-xs text-indigo-500">
                                                PNG, JPG, jusqu'à 10MB
                                            </p>
                                        </div>
                                        <input id="photos" type="file" @change="handleFileUpload" multiple
                                            class="hidden" accept="image/*" />
                                    </label>
                                </div>
                                <div v-if="previews.length"
                                    class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    <div v-for="(preview, index) in previews" :key="index" class="relative">
                                        <img :src="preview.url" class="w-full h-32 object-cover rounded-lg shadow-md" />
                                        <button @click="removePreview(index)"
                                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 transition duration-200">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <p v-if="previews.length >= 5" class="mt-2 text-sm text-red-600">
                                    Vous pouvez télécharger jusqu'à 5 photos.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="confirmDelete"
                                class="px-6 py-3 bg-red-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <span class="flex items-center">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    Supprimer
                                </span>
                            </button>
                            <button type="submit" :disabled="form.processing || form.photos.length > 5
                                "
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="flex items-center">
                                    <i class="fas fa-save mr-2"></i> Enregistrer
                                    les modifications
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
                <h2 class="text-lg font-bold mb-4">
                    Ajouter un nouveau type de propriété
                </h2>
                <input v-model="newPropertyType" type="text" class="w-full px-3 py-2 border rounded-lg mb-4"
                    placeholder="Entrez le nouveau type" />
                <div class="flex justify-end">
                    <button @click="addNewPropertyType" class="px-4 py-2 bg-indigo-600 text-white rounded-lg mr-2">
                        Ajouter
                    </button>
                    <button @click="showNewTypeModal = false" class="px-4 py-2 bg-gray-300 rounded-lg">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const { auth, property, companies, landlords: allLandlords } = usePage().props;

const propertyTypes = ref(["Appartement", "Maison", "Studio", "Loft"]);
const showNewTypeModal = ref(false);
const newPropertyType = ref("");

const form = useForm({
    name: property.name,
    property_type: property.property_type,
    description: property.description,
    address: property.address,
    available_count: property.available_count,
    landlord_id: property.landlord_id,
    company_id: property.company_id,
    price: property.price,
    photos: [],
    existingPhotos: [],
});

const previews = ref([]);

const filteredLandlords = ref(allLandlords);

onMounted(() => {
    let existingPhotos = [];
    try {
        if (property.photos && typeof property.photos === 'string') {
            existingPhotos = JSON.parse(property.photos);
        } else if (Array.isArray(property.photos)) {
            existingPhotos = property.photos;
        }
    } catch (error) {
        console.error('Error parsing property photos:', error);
    }

    if (Array.isArray(existingPhotos) && existingPhotos.length > 0) {
        previews.value = existingPhotos.map(photo => ({
            url: photo.startsWith('/') ? photo : `/storage/${photo}`,
            isExisting: true
        }));
        form.existingPhotos = existingPhotos;
    } else {
        console.log('No existing photos found or photos is not in the expected format');
        previews.value = [];
        form.existingPhotos = [];
    }
});

const updateLandlords = () => {
    if (auth.isSuperAdmin && form.company_id) {
        filteredLandlords.value = allLandlords.filter(
            (landlord) => landlord.company_id === form.company_id
        );
    } else {
        filteredLandlords.value = allLandlords;
    }
    form.landlord_id = "";
};

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);

    if (previews.value.length + files.length <= 5) {
        files.forEach((file) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                previews.value.push({
                    url: e.target.result,
                    isExisting: false,
                });
                form.photos.push(file);
            };
            reader.readAsDataURL(file);
        });
    } else {
        alert("Vous pouvez télécharger jusqu'à 5 photos au total.");
    }
};

const removePreview = (index) => {
    if (previews.value[index].isExisting) {
        const removedPhoto = form.existingPhotos.splice(index, 1)[0];
        form.append("removed_photos", removedPhoto);
    } else {
        const newPhotoIndex = index - form.existingPhotos.length;
        form.photos.splice(newPhotoIndex, 1);
    }
    previews.value.splice(index, 1);
};

const addNewPropertyType = () => {
    if (
        newPropertyType.value &&
        !propertyTypes.value.includes(newPropertyType.value)
    ) {
        propertyTypes.value.push(newPropertyType.value);
        form.property_type = newPropertyType.value;
        newPropertyType.value = "";
        showNewTypeModal.value = false;
    }
};

const validateForm = () => {
    const requiredFields = [
        "name",
        "property_type",
        "address",
        "available_count",
        "landlord_id",
        "company_id",
        "price",
    ];
    const missingFields = requiredFields.filter((field) => !form[field]);

    if (missingFields.length > 0) {
        alert(
            `Veuillez remplir tous les champs obligatoires : ${missingFields.join(
                ", "
            )}`
        );
        return false;
    }

    if (isNaN(form.price) || form.price <= 0) {
        alert("Le prix doit être un nombre positif");
        return false;
    }

    if (isNaN(form.available_count) || form.available_count < 0) {
        alert(
            "Le nombre disponible doit être un nombre entier positif ou zéro"
        );
        return false;
    }

    return true;
};

const submit = () => {
    if (!validateForm()) {
        return;
    }

    const formData = new FormData();

    Object.keys(form).forEach((key) => {
        if (key !== "photos" && key !== "existingPhotos" && key !== "_token") {
            formData.append(key, form[key]);
        }
    });

    formData.append("existingPhotos", JSON.stringify(form.existingPhotos));

    form.photos.forEach((photo, index) => {
        formData.append(`photos[${index}]`, photo);
    });

    const csrfToken = usePage().props.csrf_token;

    if (!csrfToken) {
        console.error("CSRF token not found in Inertia props");
        alert(
            "Une erreur est survenue lors de la mise à jour de la propriété. CSRF token non trouvé."
        );
        return;
    }

    formData.append("_method", "PUT");

    fetch(route("properties.update", property.id), {
    method: "POST",
    body: formData,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
    },
})
.then(response => {
    if (!response.ok) {
        return response.text().then(text => {
            throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
        });
    }
    return response.json();
})
.then(data => {
    if (data.success) {
        alert("Propriété mise à jour avec succès");
        window.location.href = route("properties.index");
    } else {
        throw new Error(data.message || "Une erreur est survenue lors de la mise à jour de la propriété");
    }
})
.catch(error => {
    console.error("Erreur:", error);
    alert(error.message || "Une erreur est survenue lors de la mise à jour de la propriété");
});
};

const confirmDelete = () => {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette propriété ?")) {
        form.delete(route("properties.destroy", property.id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                alert("Propriété supprimée avec succès");
                window.location.href = route("properties.index");
            },
            onError: (errors) => {
                console.error(errors);
                alert(
                    "Une erreur est survenue lors de la suppression de la propriété"
                );
            },
        });
    }
};

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
