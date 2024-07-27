<template>
    <div>
      <ul class="divide-y divide-gray-200">
        <li v-for="doc in documents" :key="doc.id" class="py-4 flex items-center justify-between">
          <div class="flex items-center">
            <i :class="['text-gray-400 mr-3', getIconForDocumentType(doc.type)]"></i>
            <span class="text-sm font-medium text-gray-900">{{ doc.name }}</span>
          </div>
          <div class="flex space-x-2">
            <button @click="downloadDocument(doc)" class="text-sm bg-blue-500 text-white hover:bg-blue-600 px-3 py-2 rounded-md transition duration-150 ease-in-out">
              <i class="bi-download mr-2"></i> Télécharger
            </button>
            <button @click="deleteDocument(doc)" class="text-sm border border-red-300 text-red-700 hover:bg-red-50 px-3 py-2 rounded-md transition duration-150 ease-in-out">
              <i class="bi-trash mr-2"></i> Supprimer
            </button>
          </div>
        </li>
      </ul>

      <div class="mt-4">
        <label for="file-upload" class="block text-sm font-medium text-gray-700 mb-2">
          Ajouter un nouveau document
        </label>
        <div class="flex items-center">
          <select v-model="newDocumentType" class="mr-2 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            <option value="">Sélectionner le type de document</option>
            <option value="assurance">Attestation d'assurance</option>
            <option value="etat_des_lieux">État des lieux</option>
            <option value="contrat_signe">Contrat signé</option>
            <option value="autre">Autre document</option>
          </select>
          <input
            id="file-upload"
            type="file"
            @change="handleFileUpload"
            class="sr-only"
          />
          <label
            for="file-upload"
            class="cursor-pointer bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded-md transition duration-150 ease-in-out"
          >
            <i class="bi-upload mr-2"></i> Choisir un fichier
          </label>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted  } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import axios from 'axios';

  const props = defineProps({
  contractId: {
    type: Number,
    required: true
  }
});

const documents = ref([
    { id: 1, name: "Attestation d'assurance 2023", type: "assurance" },
    { id: 2, name: "État des lieux d'entrée", type: "etat_des_lieux" },
    { id: 3, name: "Contrat signé", type: "contrat_signe" },
  ]);

  const newDocumentType = ref('');

  const form = useForm({
    file: null,
document_type: '',
  contract_id: props.contractId
});

const getIconForDocumentType = (type) => {
  switch (type) {
    case 'assurance':
      return 'bi-shield-check';
    case 'etat_des_lieux':
      return 'bi-clipboard-check';
    case 'contrat_signe':
      return 'bi-file-earmark-text';
    default:
      return 'bi-file-earmark';
  }
};

const handleFileUpload = (event) => {
  form.file = event.target.files[0];
  form.document_type = newDocumentType.value;
  uploadDocument();
};

const uploadDocument = () => {
  form.post(route('documents.store'), {
    preserveScroll: true,
    onSuccess: () => {
      // Rafraîchir la liste des documents
      fetchDocuments();
      // Réinitialiser le formulaire
      form.reset();
      newDocumentType.value = '';
    },
  });
};

const downloadDocument = (doc) => {
  window.open(route('documents.download', doc.id), '_blank');
};

const deleteDocument = (doc) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
    axios.delete(route('documents.destroy', doc.id))
      .then(() => {
        // Retirer le document de la liste
        documents.value = documents.value.filter(d => d.id !== doc.id);
      })
      .catch(error => {
        console.error('Erreur lors de la suppression du document:', error);
      });
  }
};

const fetchDocuments = async () => {
  try {
    const response = await axios.get(route('documents.index', { contract: props.contractId }));
    documents.value = response.data;
  } catch (error) {
    console.error('Erreur lors de la récupération des documents:', error);
  }
};


// Charger les documents au montage du composant
fetchDocuments();
</script>
