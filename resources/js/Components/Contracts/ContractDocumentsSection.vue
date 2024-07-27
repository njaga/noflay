<template>
    <div
        class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl overflow-hidden mt-5 shadow-lg"
    >
        <div class="bg-white px-8 py-6">
            <h4 class="text-2xl font-bold text-indigo-800 mb-6">
                Documents liés
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    v-for="doc in documents"
                    :key="doc.type"
                    class="bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-medium text-gray-700">{{
                            doc.name
                        }}</span>
                        <div class="flex items-center space-x-2">
                            <button
                                v-if="contract[doc.path]"
                                @click="downloadDocument(doc.type)"
                                class="text-indigo-600 hover:text-indigo-800 transition duration-300"
                                title="Télécharger"
                            >
                                <i class="bi bi-download text-xl"></i>
                            </button>
                            <button
                                @click="
                                    openModal(
                                        contract[doc.path] ? 'edit' : 'add',
                                        doc
                                    )
                                "
                                class="bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition duration-300"
                                :title="
                                    contract[doc.path] ? 'Modifier' : 'Ajouter'
                                "
                            >
                                <i
                                    :class="[
                                        'text-lg',
                                        contract[doc.path]
                                            ? 'bi bi-pencil-fill'
                                            : 'bi bi-plus-lg',
                                    ]"
                                ></i>
                            </button>
                            <button
                                v-if="contract[doc.path]"
                                @click="openModal('delete', doc)"
                                class="text-red-600 hover:text-red-800 transition duration-300"
                                title="Supprimer"
                            >
                                <i class="bi bi-trash text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Modal :show="isModalOpen" @close="closeModal">
        <template #title>
            {{ modalTitle }}
        </template>
        <template #body>
            <p class="text-sm text-gray-500 mb-4">{{ modalDescription }}</p>
            <input
                v-if="modalType !== 'delete'"
                type="file"
                @change="handleFileChange"
                class="w-full p-2 border border-gray-300 rounded"
            />
        </template>
        <template #footer>
            <button
                @click="handleModalAction"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-300"
            >
                {{ modalActionText }}
            </button>
            <button
                @click="closeModal"
                class="ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition duration-300"
            >
                Annuler
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, computed } from "vue";
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    contract: {
        type: Object,
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

const form = useForm({
    document_type: "",
    file: null,
});

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
    form.reset();
};

const handleFileChange = (event) => {
    form.file = event.target.files[0];
};

const handleModalAction = () => {
    if (modalType.value === "delete") {
        deleteDocument();
    } else {
        uploadDocument();
    }
};

const uploadDocument = () => {
    form.document_type = selectedDocument.value.type;
    form.post(route("contracts.upload-document", props.contract.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const downloadDocument = (documentType) => {
    window.location.href = route("contracts.download-document", [
        props.contract.id,
        documentType,
    ]);
};

const deleteDocument = () => {
    form.delete(
        route("contracts.delete-document", [
            props.contract.id,
            selectedDocument.value.type,
        ]),
        {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        }
    );
};
</script>
