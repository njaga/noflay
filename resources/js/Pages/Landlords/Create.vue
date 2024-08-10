<template>
    <AppLayout title="Créer un bailleur">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div
                class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden"
            >
                <div class="p-8 sm:p-12">
                    <h1
                        class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-8 animate-gradient"
                    >
                        Créer un bailleur
                    </h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Prénom -->
                            <div class="space-y-2">
                                <label
                                    for="first_name"
                                    class="text-sm font-medium text-gray-700"
                                    >Prénom</label
                                >
                                <input
                                    id="first_name"
                                    v-model="form.first_name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Nom -->
                            <div class="space-y-2">
                                <label
                                    for="last_name"
                                    class="text-sm font-medium text-gray-700"
                                    >Nom</label
                                >
                                <input
                                    id="last_name"
                                    v-model="form.last_name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Adresse -->
                            <div class="space-y-2">
                                <label
                                    for="address"
                                    class="text-sm font-medium text-gray-700"
                                    >Adresse</label
                                >
                                <input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Téléphone -->
                            <div class="space-y-2">
                                <label
                                    for="phone"
                                    class="text-sm font-medium text-gray-700"
                                    >Téléphone</label
                                >
                                <vue-tel-input
                                    v-model="form.phone"
                                    @validate="validatePhoneNumber"
                                    :defaultCountry="'SN'"
                                    :preferredCountries="['SN', 'FR', 'US']"
                                    :enabledFlags="true"
                                    :inputClasses="'w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent'"
                                    :wrapperClasses="'hover:scale-105 transition duration-200 ease-in-out'"
                                    :dropdownOptions="{
                                        showDialCodeInSelection: true,
                                        showFlags: true,
                                        showSearchBox: true,
                                        searchPlaceholder: 'Rechercher',
                                    }"
                                    :inputOptions="{
                                        autocomplete: 'tel',
                                        id: 'phone',
                                        placeholder:
                                            'Entrez votre numéro de téléphone',
                                    }"
                                    mode="international"
                                    :validCharactersOnly="true"
                                    :autoFormat="true"
                                    :maxLen="25"
                                    :required="true"
                                    :error="!!phoneError"
                                    :error-messages="
                                        phoneError ? [phoneError] : []
                                    "
                                >
                                </vue-tel-input>
                                <span
                                    v-if="phoneError"
                                    class="text-red-500 text-sm"
                                    >{{ phoneError }}</span
                                >
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label
                                    for="email"
                                    class="text-sm font-medium text-gray-700"
                                    >Email</label
                                >
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Numéro CNI ou passeport -->
                            <div class="space-y-2">
                                <label
                                    for="identity_number"
                                    class="text-sm font-medium text-gray-700"
                                    >Numéro CNI ou passeport</label
                                >
                                <input
                                    id="identity_number"
                                    v-model="form.identity_number"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Date d'expiration -->
                            <div class="space-y-2">
                                <label
                                    for="identity_expiry_date"
                                    class="text-sm font-medium text-gray-700"
                                    >Date d'expiration CNI ou passeport</label
                                >
                                <input
                                    id="identity_expiry_date"
                                    v-model="form.identity_expiry_date"
                                    type="date"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Pourcentage de gestion -->
                            <div class="space-y-2">
                                <label
                                    for="agency_percentage"
                                    class="text-sm font-medium text-gray-700"
                                    >Pourcentage de gestion</label
                                >
                                <input
                                    id="agency_percentage"
                                    v-model="form.agency_percentage"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Pourcentage locatif -->
                            <div class="space-y-2">
                                <label
                                    for="rental_percentage"
                                    class="text-sm font-medium text-gray-700"
                                    >Pourcentage locatif</label
                                >
                                <input
                                    id="rental_percentage"
                                    v-model="form.rental_percentage"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Durée de contrat -->
                            <div class="space-y-2">
                                <label
                                    for="contract_duration"
                                    class="text-sm font-medium text-gray-700"
                                    >Durée de contrat (en
                                    mois)</label
                                >
                                <input
                                    id="contract_duration"
                                    v-model="form.contract_duration"
                                    type="number"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                />
                            </div>

                            <!-- Sélection d'entreprise (si super admin) -->
                            <div v-if="auth.isSuperAdmin" class="space-y-2">
                                <label
                                    for="company_id"
                                    class="text-sm font-medium text-gray-700"
                                    >Entreprise</label
                                >
                                <select
                                    id="company_id"
                                    v-model="form.company_id"
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105"
                                >
                                    <option value="">
                                        Sélectionnez une entreprise
                                    </option>
                                    <option
                                        v-for="company in companies"
                                        :key="company.id"
                                        :value="company.id"
                                    >
                                        {{ company.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Pièces-jointes -->
                            <div class="space-y-2 sm:col-span-2">
                                <label
                                    for="attachments"
                                    class="text-sm font-medium text-gray-700"
                                    >Pièces-jointes</label
                                >
                                <div
                                    class="flex items-center justify-center w-full"
                                >
                                    <label
                                        for="attachments"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-indigo-300 border-dashed rounded-lg cursor-pointer bg-indigo-50 hover:bg-indigo-100 transition duration-300 ease-in-out"
                                    >
                                        <div
                                            class="flex flex-col items-center justify-center pt-5 pb-6"
                                        >
                                            <svg
                                                class="w-10 h-10 mb-3 text-indigo-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                                ></path>
                                            </svg>
                                            <p
                                                class="mb-2 text-sm text-indigo-600"
                                            >
                                                <span class="font-bold"
                                                    >Cliquez pour
                                                    télécharger</span
                                                >
                                                ou glissez-déposez
                                            </p>
                                            <p class="text-xs text-indigo-500">
                                                PNG, JPG, PDF jusqu'à 5MB (max 5
                                                fichiers)
                                            </p>
                                        </div>
                                        <input
                                            id="attachments"
                                            type="file"
                                            @change="handleFileUpload"
                                            multiple
                                            class="hidden"
                                            accept=".png,.jpg,.jpeg,.pdf"
                                        />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu des fichiers joints -->
                        <div
                            v-if="previewFiles.length > 0"
                            class="mt-6 bg-gray-50 p-4 rounded-lg"
                        >
                            <h3 class="text-lg font-medium text-gray-900 mb-3">
                                Fichiers joints:
                            </h3>
                            <ul class="space-y-3">
                                <li
                                    v-for="(file, index) in previewFiles"
                                    :key="index"
                                    class="flex items-center justify-between p-3 bg-white rounded-md shadow-sm"
                                >
                                    <div class="flex items-center space-x-3">
                                        <span
                                            v-if="file.type.startsWith('image')"
                                            class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden"
                                        >
                                            <img
                                                :src="file.preview"
                                                :alt="file.name"
                                                class="h-full w-full object-cover"
                                            />
                                        </span>
                                        <span
                                            v-else
                                            class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center"
                                        >
                                            <svg
                                                class="h-6 w-6 text-gray-400"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                                />
                                            </svg>
                                        </span>
                                        <span
                                            class="flex-1 text-sm font-medium text-gray-900 truncate"
                                        >
                                            {{ file.name }}
                                        </span>
                                    </div>
                                    <button
                                        @click="removeFile(index)"
                                        type="button"
                                        class="ml-4 flex-shrink-0 text-sm font-medium text-red-600 hover:text-red-500 transition duration-150 ease-in-out"
                                    >
                                        Supprimer
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <span class="flex items-center">
                                    <svg
                                        class="w-5 h-5 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"
                                        ></path>
                                    </svg>
                                    Enregistrer
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de succès -->
        <TransitionRoot as="template" :show="showSuccessModal">
            <Dialog
                as="div"
                class="fixed z-10 inset-0 overflow-y-auto"
                @close="closeSuccessModal"
            >
                <div
                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        />
                    </TransitionChild>

                    <span
                        class="hidden sm:inline-block sm:align-middle sm:h-screen"
                        aria-hidden="true"
                        >&#8203;</span
                    >

                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div
                            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6"
                        >
                            <div>
                                <div
                                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100"
                                >
                                    <CheckIcon
                                        class="h-6 w-6 text-green-600"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <DialogTitle
                                        as="h3"
                                        class="text-lg leading-6 font-medium text-gray-900"
                                    >
                                        Bailleur créé avec succès
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Le bailleur a été ajouté à la base
                                            de données avec succès.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                                    @click="closeSuccessModal"
                                >
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Modal d'erreur -->
        <TransitionRoot as="template" :show="showErrorModal">
            <Dialog
                as="div"
                class="fixed z-10 inset-0 overflow-y-auto"
                @close="closeErrorModal"
            >
                <div
                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        />
                    </TransitionChild>

                    <span
                        class="hidden sm:inline-block sm:align-middle sm:h-screen"
                        aria-hidden="true"
                        >&#8203;</span
                    >

                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div
                            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6"
                        >
                            <div>
                                <div
                                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        strokeWidth="{1.5}"
                                        stroke="currentColor"
                                        className="size-6"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
                                        />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <DialogTitle
                                        as="h3"
                                        class="text-lg leading-6 font-medium text-gray-900"
                                    >
                                        Erreur
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            {{ errorMessage }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm"
                                    @click="closeErrorModal"
                                >
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </AppLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon } from "@heroicons/vue/24/outline";
import { parsePhoneNumber, isValidPhoneNumber } from "libphonenumber-js";

const { auth, companies } = usePage().props;

const form = useForm({
    first_name: "",
    last_name: "",
    address: "",
    phone: "",
    email: "",
    identity_number: "",
    identity_expiry_date: "",
    agency_percentage: "",
    rental_percentage: "",
    contract_duration: "",
    company_id: auth.isSuperAdmin ? "" : auth.user.company_id,
    attachments: [],
});

const previewFiles = ref([]);
const showErrorModal = ref(false);
const showSuccessModal = ref(false);
const errorMessage = ref("");
const phoneError = ref("");

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    if (files.length > 5) {
        showErrorModal.value = true;
        errorMessage.value = "Vous ne pouvez ajouter que 5 documents maximum.";
        return;
    }

    const validFiles = files.filter((file) => {
        const validTypes = ["application/pdf", "image/jpeg", "image/png"];
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (!validTypes.includes(file.type)) {
            showErrorModal.value = true;
            errorMessage.value = `Le fichier ${file.name} n'est pas d'un type valide. Seuls PDF, JPG et PNG sont acceptés.`;
            return false;
        }
        if (file.size > maxSize) {
            showErrorModal.value = true;
            errorMessage.value = `Le fichier ${file.name} est trop volumineux. La taille maximale est de 5MB.`;
            return false;
        }
        return true;
    });

    form.attachments = validFiles;
    previewFiles.value = validFiles.map((file) => ({
        name: file.name,
        type: file.type,
        preview: file.type.startsWith("image")
            ? URL.createObjectURL(file)
            : null,
    }));
};

const removeFile = (index) => {
    form.attachments.splice(index, 1);
    if (previewFiles.value[index].preview) {
        URL.revokeObjectURL(previewFiles.value[index].preview);
    }
    previewFiles.value.splice(index, 1);
};

const closeErrorModal = () => {
    showErrorModal.value = false;
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
};

const validatePhoneNumber = (phoneData) => {
    if (phoneData && phoneData.number) {
        try {
            const parsedNumber = parsePhoneNumber(phoneData.number);
            if (parsedNumber && isValidPhoneNumber(phoneData.number)) {
                phoneError.value = "";
                form.phone = parsedNumber.format("E.164");
            } else {
                phoneError.value = "Numéro de téléphone invalide";
                form.phone = "";
            }
        } catch (error) {
            phoneError.value = "Numéro de téléphone invalide";
            form.phone = "";
        }
    } else {
        phoneError.value = "";
        form.phone = "";
    }
};

watch(
    () => form.phone,
    (newValue) => {
        if (!newValue || newValue.length === 0) {
            phoneError.value = "";
        }
    }
);

const submit = () => {
    if (form.phone && phoneError.value) {
        return;
    }

    form.post(route("landlords.store"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showSuccessModal.value = true;
            form.reset();
            previewFiles.value = [];
            phoneError.value = "";
        },
        onError: (errors) => {
            console.error(errors);
            let errorMessages = [];

            if (errors.identity_expiry_date) {
                errorMessages.push(
                    "La date d'expiration de la carte d'identité ou du passeport doit être une date future."
                );
            }
            if (errors.phone) {
                phoneError.value = errors.phone;
                errorMessages.push(errors.phone);
            }
            if (errors.attachments) {
                errorMessages.push(
                    "Une erreur est survenue lors du téléchargement des pièces jointes. Veuillez réessayer."
                );
            }

            // Check for other errors
            for (let field in errors) {
                if (
                    !["identity_expiry_date", "phone", "attachments"].includes(
                        field
                    )
                ) {
                    errorMessages.push(errors[field]);
                }
            }

            if (errorMessages.length > 0) {
                errorMessage.value = errorMessages.join("\n");
                showErrorModal.value = true;
            }
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

/* Styles pour le champ téléphone */
:deep(.vue-tel-input) {
    border-radius: 0.5rem !important;
}

:deep(.vti__dropdown) {
    border-radius: 0.5rem 0 0 0.5rem !important;
    border: none !important;
    background: transparent !important;
}

:deep(.vti__input) {
    background-color: transparent !important;
    border-radius: 0 0.5rem 0.5rem 0 !important;
}

:deep(.vti__dropdown-list) {
    z-index: 50 !important;
    border-radius: 0.5rem !important;
    margin-top: 4px;
    max-height: 300px;
    overflow-y: auto;
}

:deep(.vti__dropdown-item) {
    padding: 10px;
}

:deep(.vti__dropdown-item.highlighted) {
    background-color: #eee;
}
</style>
