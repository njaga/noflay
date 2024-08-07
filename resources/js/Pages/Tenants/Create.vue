<template>
    <AppLayout title="Ajouter un locataire">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <h1
                        class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-8 animate-gradient">
                        Ajouter un locataire
                    </h1>

                    <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="first_name" class="text-sm font-medium text-gray-700">Prénom</label>
                                <input id="first_name" v-model="form.first_name" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.first_name" class="text-red-500 text-xs mt-1">{{
                                    form.errors.first_name }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="last_name" class="text-sm font-medium text-gray-700">Nom</label>
                                <input id="last_name" v-model="form.last_name" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.last_name" class="text-red-500 text-xs mt-1">{{
                                    form.errors.last_name }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                                <input id="email" v-model="form.email" type="email" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="phone_number" class="text-sm font-medium text-gray-700">Téléphone</label>
                                <input id="phone_number" v-model="form.phone_number" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.phone_number" class="text-red-500 text-xs mt-1">{{
                                    form.errors.phone_number }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="address" class="text-sm font-medium text-gray-700">Adresse</label>
                                <input id="address" v-model="form.address" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address
                                    }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="id_card_number" class="text-sm font-medium text-gray-700">Numéro
                                    CNI/Passport</label>
                                <input id="id_card_number" v-model="form.id_card_number" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.id_card_number" class="text-red-500 text-xs mt-1">{{
                                    form.errors.id_card_number }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="id_card_expiration_date" class="text-sm font-medium text-gray-700">Date
                                    d'expiration</label>
                                <input id="id_card_expiration_date" v-model="form.id_card_expiration_date" type="date"
                                    required :min="today" @change="validateExpirationDate"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.id_card_expiration_date" class="text-red-500 text-xs mt-1">{{
                                    form.errors.id_card_expiration_date }}</div>
                            </div>

                            <div v-if="isSuperAdmin" class="space-y-2">
                                <label for="company_id" class="text-sm font-medium text-gray-700">Entreprise</label>
                                <select id="company_id" v-model="form.company_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">Sélectionnez une entreprise</option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">
                                        {{ company.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.company_id" class="text-red-500 text-xs mt-1">{{
                                    form.errors.company_id }}</div>
                            </div>

                            <div class="space-y-2 sm:col-span-2">
                                <label for="attachments" class="text-sm font-medium text-gray-700">Pièces-jointes (max 5
                                    fichiers)</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="attachments"
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
                                                    télécharger</span> ou glissez-déposez</p>
                                            <p class="text-xs text-indigo-500">PNG, JPG, PDF, DOCX (taille max: 5MB par
                                                fichier)</p>
                                        </div>
                                        <input id="attachments" type="file" @change="handleFileUpload" multiple
                                            class="hidden" accept=".png,.jpg,.jpeg,.pdf,.docx" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Prévisualisation des fichiers -->
                        <div v-if="previewFiles.length > 0" class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Fichiers joints:</h3>
                            <ul class="space-y-3">
                                <li v-for="(file, index) in previewFiles" :key="index"
                                    class="flex items-center justify-between p-3 bg-white rounded-md shadow-sm">
                                    <div class="flex items-center space-x-3">
                                        <span v-if="file.type.startsWith('image')"
                                            class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden">
                                            <img :src="file.preview" :alt="file.name"
                                                class="h-full w-full object-cover" />
                                        </span>
                                        <span v-else
                                            class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </span>
                                        <span class="flex-1 text-sm font-medium text-gray-900 truncate">
                                            {{ file.name }}
                                        </span>
                                    </div>
                                    <button @click="removeFile(index)" type="button"
                                        class="ml-4 flex-shrink-0 text-sm font-medium text-red-600 hover:text-red-500 transition duration-150 ease-in-out">
                                        Supprimer
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="form.processing"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                        </path>
                                    </svg>
                                    Ajouter
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de succès -->
        <Transition name="modal">
            <div v-if="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                        <div>
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Locataire créé avec succès
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Le nouveau locataire a été ajouté à votre liste avec succès.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <button type="button" @click="showSuccessModal = false"
                                class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { auth, companies } = usePage().props;

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    address: '',
    id_card_number: '',
    id_card_expiration_date: '',
    company_id: auth.user.roles.includes('super_admin') ? '' : auth.user.company_id,
    attachments: [],
});

const previewFiles = ref([]);
const showSuccessModal = ref(false);

const isSuperAdmin = computed(() => auth.user.roles.includes('super_admin'));

const today = new Date().toISOString().split('T')[0]; // Pour la validation de la date

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    const allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
    const maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
    const maxFiles = 5;

    if (form.attachments.length + files.length > maxFiles) {
        alert(`Vous ne pouvez ajouter que ${maxFiles} fichiers au maximum.`);
        return;
    }

    const validFiles = files.filter(file => {
        if (!allowedTypes.includes(file.type)) {
            alert(`Le fichier "${file.name}" n'est pas d'un type autorisé. Seuls les fichiers PDF, DOCX et images sont autorisés.`);
            return false;
        }
        if (file.size > maxFileSize) {
            alert(`Le fichier "${file.name}" dépasse la taille maximale autorisée de 5MB.`);
            return false;
        }
        return true;
    });

    form.attachments = [...form.attachments, ...validFiles];
    previewFiles.value = [...previewFiles.value, ...validFiles.map(file => ({
        name: file.name,
        type: file.type,
        preview: file.type.startsWith('image') ? URL.createObjectURL(file) : null
    }))];
};

const removeFile = (index) => {
    form.attachments.splice(index, 1);
    if (previewFiles.value[index].preview) {
        URL.revokeObjectURL(previewFiles.value[index].preview);
    }
    previewFiles.value.splice(index, 1);
};

const validateExpirationDate = () => {
    const selectedDate = new Date(form.id_card_expiration_date);
    const currentDate = new Date();

    if (selectedDate <= currentDate) {
        form.errors.id_card_expiration_date = "La date d'expiration doit être dans le futur.";
    } else {
        delete form.errors.id_card_expiration_date;
    }
};

const submit = () => {
    validateExpirationDate();

    if (Object.keys(form.errors).length > 0) {
        return;
    }

    form.post(route('tenants.store'), {
        preserveScroll: true,
        preserveState: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            previewFiles.value = [];
            showSuccessModal.value = true;
        },
        onError: (errors) => {
            console.error('Erreur lors de la création du locataire:', errors);
            alert('Une erreur est survenue lors de la création du locataire. Veuillez vérifier les informations saisies et réessayer.');
        },
    });
};

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

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
