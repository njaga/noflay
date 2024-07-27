<template>
    <AppLayout title="Détails du Document">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-2xl font-semibold mb-6">{{ document.name }}</h1>
            <div class="mb-4">
              <strong>Type:</strong> {{ document.document_type }}
            </div>
            <div class="mb-4">
              <strong>Téléchargé par:</strong> {{ document.uploaded_by }}
            </div>
            <div class="mb-4">
              <strong>Date de création:</strong> {{ formatDate(document.created_at) }}
            </div>
            <div class="mt-6">
              <Link :href="route('documents.edit', document.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Modifier
              </Link>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script>
  import { defineComponent } from 'vue'
  import AppLayout from '@/Layouts/AppLayout.vue'
  import { usePage, Link, router } from "@inertiajs/vue3";

  export default defineComponent({
    components: {
      AppLayout,
      Link
    },
    props: {
      document: Object
    },
    methods: {
      formatDate(date) {
        return new Date(date).toLocaleDateString()
      }
    }
  })
  </script>
