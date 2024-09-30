<template>
    <div>
      <div v-for="docType in documentTypes" :key="docType.id" class="mb-6">
        <h4 class="text-md font-medium text-gray-700 mb-2">{{ docType.label }}</h4>
        <div v-if="getDocumentByType(docType.id)" class="flex items-center justify-between bg-gray-100 p-3 rounded">
          <span class="text-sm text-gray-800">{{ getDocumentByType(docType.id).name }}</span>
          <div class="flex space-x-2">
            <button @click="downloadDocument(getDocumentByType(docType.id))" class="text-sm border border-gray-300 text-gray-700 hover:bg-gray-200 px-3 py-1 rounded-md">
              <i class="bi-download mr-1"></i> Télécharger
            </button>
            <button @click="removeDocument(getDocumentByType(docType.id))" class="text-sm border border-gray-300 text-gray-700 hover:bg-gray-200 px-3 py-1 rounded-md">
              <i class="bi-trash mr-1"></i> Supprimer
            </button>
          </div>
        </div>
        <div v-else class="mt-2">
          <label :for="'file-' + docType.id" class="block text-sm font-medium text-gray-700 mb-1">
            Ajouter {{ docType.label }}
          </label>
          <input
            :id="'file-' + docType.id"
            type="file"
            @change="(e) => handleFileChange(e, docType.id)"
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
          />
        </div>
      </div>

      <div class="mt-6">
        <h4 class="text-md font-medium text-gray-700 mb-2">Autres documents</h4>
        <ul class="divide-y divide-gray-200">
          <li v-for="doc in otherDocuments" :key="doc.id" class="py-4 flex items-center justify-between">
            <span class="text-sm text-gray-800">{{ doc.name }}</span>
            <div class="flex space-x-2">
              <button @click="downloadDocument(doc)" class="text-sm border border-gray-300 text-gray-700 hover:bg-gray-200 px-3 py-1 rounded-md">
                <i class="bi-download mr-1"></i> Télécharger
              </button>
              <button @click="removeDocument(doc)" class="text-sm border border-gray-300 text-gray-700 hover:bg-gray-200 px-3 py-1 rounded-md">
                <i class="bi-trash mr-1"></i> Supprimer
              </button>
            </div>
          </li>
        </ul>
        <div class="mt-2">
          <label for="file-other" class="block text-sm font-medium text-gray-700 mb-1">
            Ajouter un autre document
          </label>
          <input
            id="file-other"
            type="file"
            @change="(e) => handleFileChange(e, 'other')"
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
          />
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, computed, watch } from 'vue';
  import { router } from "@inertiajs/vue3";

  const props = defineProps({
    documents: Array,
    contractId: Number,
  });

  const documentTypes = [
    { id: 'insurance', label: "Attestation d'assurance" },
    { id: 'inventory', label: "État des lieux" },
    { id: 'signed_contract', label: "Contrat signé" },
  ];

  const getDocumentByType = (type) => {
    const doc = props.documents.find(doc => doc.document_type === type);
    console.log(`Searching for document type: ${type}`, doc);
    return doc;
  };

  const otherDocuments = computed(() => {
    const others = props.documents.filter(doc => !documentTypes.some(type => type.id === doc.document_type));
    console.log('Other documents:', others);
    return others;
  });

  watch(() => props.documents, (newDocs) => {
    console.log('Documents updated:', newDocs);
  }, { deep: true });

  const handleFileChange = (event, docType) => {
    const file = event.target.files[0];
    uploadDocument(file, docType);
  };

  const uploadDocument = (file, docType) => {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('document_type', docType);
    formData.append('contract_id', props.contractId);

    router.post(route('documents.store'), formData, {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        console.log('Document uploaded successfully');
        emit('documentUploaded');
      },
      onError: (errors) => {
        console.error('Error uploading document:', errors);
      },
    });
  };

  const downloadDocument = (doc) => {
    window.open(route('documents.download', doc.id), '_blank');
  };

  const removeDocument = (doc) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
      router.delete(route('documents.destroy', doc.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          console.log('Document removed successfully');
          emit('documentRemoved');
        },
        onError: (errors) => {
          console.error('Error removing document:', errors);
        },
      });
    }
  };

  const emit = defineEmits(['documentUploaded', 'documentRemoved']);
  </script>
