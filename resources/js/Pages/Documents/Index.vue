<template>
    <AppLayout title="Documents">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <h1 class="text-2xl font-semibold mb-6">Liste des Documents</h1>
          <div v-if="documents.length === 0">Aucun document trouvé.</div>
          <div v-else class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <DocumentList :documents="documents" :contractId="contractId" @documentUploaded="fetchDocuments" @documentRemoved="fetchDocuments" />
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import DocumentList from '@/Components/DocumentList.vue';
  import { usePage } from "@inertiajs/vue3";

  const props = defineProps({
    initialDocuments: {
      type: Array,
      default: () => [],
    },
    contractId: {
      type: Number,
      required: true,
    },
  });

  const documents = ref(props.initialDocuments);

  const fetchDocuments = async () => {
    try {
      const response = await fetch(route('documents.index', { contract: props.contractId }));
      const data = await response.json();
      console.log('Fetched documents in Index:', data);
      documents.value = data;
    } catch (error) {
      console.error('Erreur lors de la récupération des documents:', error);
    }
  };

  onMounted(() => {
    if (documents.value.length === 0) {
      fetchDocuments();
    } else {
      console.log('Initial documents:', documents.value);
    }
  });

  watch(() => documents.value, (newDocs) => {
    console.log('Documents in Index updated:', newDocs);
  }, { deep: true });
  </script>
