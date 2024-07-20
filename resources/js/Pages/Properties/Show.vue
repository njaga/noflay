<template>
    <AppLayout title="Détails de la propriété">
      <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
          <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-900 mb-8">
            <i class="fas fa-building mr-2 text-indigo-600"></i>Détails de la propriété
          </h1>
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900">{{ property.name }}</h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">Informations sur la propriété.</p>
            </div>
            <div class="border-t border-gray-200">
              <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Type de propriété</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.property_type }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Nom</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.name }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Description</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.description }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.address }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Nombre libre</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.available_count }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Bailleur</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.landlord.first_name }} {{ property.landlord.last_name }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ property.company.name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Photos</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div v-if="parsedPhotos.length" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      <div v-for="(photo, index) in parsedPhotos" :key="index" class="relative group">
                        <img
                          :src="`/storage/${photo}`"
                          :alt="`Photo de la propriété ${index + 1}`"
                          class="w-full h-32 object-cover rounded-md transition-opacity duration-300 group-hover:opacity-75"
                        />
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                          <button @click="openLightbox(index)" class="bg-indigo-600 text-white px-4 py-2 rounded-md">
                            Agrandir
                          </button>
                        </div>
                      </div>
                    </div>
                    <p v-else class="text-gray-500 italic">Aucune photo disponible</p>
                  </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Locataires</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <ul v-if="property.tenants && property.tenants.length" class="list-disc ml-5">
                      <li v-for="tenant in property.tenants" :key="tenant.id">{{ tenant.name }}</li>
                    </ul>
                    <p v-else class="text-gray-500 italic">Aucun locataire pour le moment</p>
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Lightbox pour les photos -->
      <div v-if="lightboxOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
        <div class="relative max-w-3xl w-full">
          <img
            :src="`/storage/${parsedPhotos[currentPhotoIndex]}`"
            :alt="`Photo de la propriété ${currentPhotoIndex + 1}`"
            class="w-full h-auto"
          />
          <button @click="closeLightbox" class="absolute top-4 right-4 text-white text-2xl">&times;</button>
          <button @click="previousPhoto" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-4xl">&lt;</button>
          <button @click="nextPhoto" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-4xl">&gt;</button>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';

  const { props } = usePage();
  const property = props.property;

  const parsedPhotos = computed(() => {
    return property.photos ? JSON.parse(property.photos) : [];
  });

  const lightboxOpen = ref(false);
  const currentPhotoIndex = ref(0);

  const openLightbox = (index) => {
    currentPhotoIndex.value = index;
    lightboxOpen.value = true;
  };

  const closeLightbox = () => {
    lightboxOpen.value = false;
  };

  const previousPhoto = () => {
    currentPhotoIndex.value = (currentPhotoIndex.value - 1 + parsedPhotos.value.length) % parsedPhotos.value.length;
  };

  const nextPhoto = () => {
    currentPhotoIndex.value = (currentPhotoIndex.value + 1) % parsedPhotos.value.length;
  };
  </script>
