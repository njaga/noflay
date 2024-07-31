<template>
    <div
        class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg shadow-md overflow-hidden"
    >
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xl font-semibold text-indigo-800 mb-4">
                        Locataire
                    </h4>
                    <p class="flex items-center mb-2">
                        <i class="bi bi-person-fill text-indigo-600 mr-2"></i>
                        <span class="font-medium mr-2">Nom:</span>
                        {{ contract.tenant.first_name }}
                        {{ contract.tenant.last_name }}
                    </p>
                    <p class="flex items-center mb-2">
                        <i class="bi bi-envelope-fill text-indigo-600 mr-2"></i>
                        <span class="font-medium mr-2">Email:</span>
                        {{ contract.tenant.email }}
                    </p>
                    <p class="flex items-center mb-2">
                        <i class="bi bi-telephone-fill text-indigo-600 mr-2"></i>
                        <span class="font-medium mr-2">Téléphone:</span>
                        {{ contract.tenant.phone_number || "N/A" }}
                    </p>
                </div>
                <div>
                    <h4 class="text-xl font-semibold text-indigo-800 mb-4">
                        Propriété
                    </h4>
                    <p class="flex items-center mb-2">
                        <i class="bi bi-house-fill text-indigo-600 mr-2"></i>
                        <span class="font-medium mr-2">Nom:</span>
                        {{ contract.property.name }}
                    </p>
                    <p class="flex items-center mb-2">
                        <i class="bi bi-geo-alt-fill text-indigo-600 mr-2"></i>
                        <span class="font-medium mr-2">Adresse:</span>
                        {{ contract.property.address }}
                    </p>
                    <p class="flex items-center mb-2">
                        <i class="bi bi-building text-indigo-600 mr-2"></i>
                        <span class="font-medium mr-2">Type:</span>
                        {{ contract.property.property_type }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white px-8 py-6">
            <h4 class="text-xl font-semibold text-indigo-800 mb-6">
                Documents liés
            </h4>
            <ul class="space-y-4">
                <li
                    v-for="doc in documents"
                    :key="doc.type"
                    class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm"
                >
                    <span class="text-sm text-gray-700">{{ doc.name }}</span>
                    <div class="flex items-center space-x-2">
                        <button
                            v-if="contract[doc.path]"
                            @click="downloadDocument(doc.type)"
                            class="text-indigo-600 hover:text-indigo-800 transition duration-300"
                            title="Télécharger"
                        >
                            <i class="bi bi-download"></i>
                        </button>
                        <button
                            @click="
                                openModal(
                                    contract[doc.path] ? 'edit' : 'add',
                                    doc
                                )
                            "
                            class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-full hover:bg-indigo-700 transition duration-300 flex items-center"
                        >
                            <i v-if="contract[doc.path]" class="bi bi-pencil-fill mr-2"></i>
                            {{ contract[doc.path] ? "Modifier" : "Ajouter" }}
                        </button>
                        <button
                            v-if="contract[doc.path]"
                            @click="openModal('delete', doc)"
                            class="text-red-600 hover:text-red-800 transition duration-300"
                            title="Supprimer"
                        >
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Modal -->
    <Teleport to="body">
        <div
            v-if="isModalOpen"
            class="fixed z-10 inset-0 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    aria-hidden="true"
                ></div>
                <span
                    class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true"
                    >&#8203;</span
                >
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                    id="modal-title"
                                >
                                    {{ modalTitle }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ modalDescription }}
                                    </p>
                                    <input
                                        v-if="modalType !== 'delete'"
                                        type="file"
                                        @change="handleFileChange"
                                        class="mt-4"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <button
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="handleModalAction"
                        >
                            {{ modalActionText }}
                        </button>
                        <button
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="closeModal"
                        >
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, computed } from "vue";
import axios from "axios";

const props = defineProps({
    contract: {
        type: Object,
        required: true,
    },
    totalCommission: {
        type: Number,
        required: true,
    },
});
const documents = [
    {
        type: "contract_signed",
        name: "Contrat signé",
        path: "contract_signed_path",
    },
    {
        type: "insurance",
        name: "Attestation d'assurance",
        path: "insurance_path",
    },
    { type: "inventory", name: "État des lieux", path: "inventory_path" },
    {
        type: "other_documents",
        name: "Autres documents",
        path: "other_documents_path",
    },
];

const isModalOpen = ref(false);
const modalType = ref("");
const selectedDocument = ref(null);
const selectedFile = ref(null);

const modalTitle = computed(() => {
    switch (modalType.value) {
        case "add":
            return "Ajouter un document";
        case "edit":
            return "Modifier le document";
        case "delete":
            return "Supprimer le document";
        default:
            return "";
    }
});

const modalDescription = computed(() => {
    switch (modalType.value) {
        case "add":
            return "Veuillez sélectionner le fichier à ajouter.";
        case "edit":
            return "Veuillez sélectionner le nouveau fichier pour remplacer l'existant.";
        case "delete":
            return "Êtes-vous sûr de vouloir supprimer ce document ? Cette action est irréversible.";
        default:
            return "";
    }
});

const modalActionText = computed(() => {
    switch (modalType.value) {
        case "add":
            return "Ajouter";
        case "edit":
            return "Modifier";
        case "delete":
            return "Supprimer";
        default:
            return "";
    }
});

const openModal = (type, document = null) => {
    modalType.value = type;
    selectedDocument.value = document;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedDocument.value = null;
    selectedFile.value = null;
};

const handleFileChange = (event) => {
    selectedFile.value = event.target.files[0];
};

const handleModalAction = async () => {
    if (modalType.value === "delete") {
        await deleteDocument();
    } else {
        await uploadDocument();
    }
    closeModal();
};

const uploadDocument = async () => {
    if (!selectedFile.value || !selectedDocument.value) return;

    const formData = new FormData();
    formData.append("file", selectedFile.value);
    formData.append("document_type", selectedDocument.value.type);

    try {
        const response = await axios.post(
            `/contracts/${props.contract.id}/upload-document`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );
        console.log(response.data.message);
        props.contract[selectedDocument.value.path] = true;
    } catch (error) {
        console.error("Erreur lors de l'upload du document:", error);
        alert(
            "Une erreur est survenue lors de l'upload du document. Veuillez réessayer."
        );
    }
};

const downloadDocument = async (documentType) => {
    try {
        const response = await axios.get(
            `/contracts/${props.contract.id}/download-document/${documentType}`,
            {
                responseType: "blob",
            }
        );
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", `${documentType}.pdf`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error("Erreur lors du téléchargement du document:", error);
        alert(
            "Une erreur est survenue lors du téléchargement du document. Veuillez réessayer."
        );
    }
};

const deleteDocument = async () => {
    if (!selectedDocument.value) return;

    try {
        const response = await axios.delete(
            `/contracts/${props.contract.id}/delete-document/${selectedDocument.value.type}`
        );
        console.log(response.data.message);
        props.contract[selectedDocument.value.path] = null;
    } catch (error) {
        console.error("Erreur lors de la suppression du document:", error);
        alert(
            "Une erreur est survenue lors de la suppression du document. Veuillez réessayer."
        );
    }
};
</script>

<style>
/* Ajoutez ici les styles personnalisés si nécessaire */
</style>
