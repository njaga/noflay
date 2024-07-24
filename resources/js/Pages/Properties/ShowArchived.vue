<template>
    <AppLayout :title="`Détails de la propriété archivée - ${property.name}`">
      <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <div class="bg-white shadow-xl rounded-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02]">
            <!-- En-tête -->
            <div class="bg-indigo-700 text-white p-6">
              <h1 class="text-3xl font-extrabold">{{ property.name }}</h1>
              <p class="mt-2 text-indigo-200">{{ property.description }}</p>
            </div>

            <!-- Contenu principal -->
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Informations de base -->
                <div class="bg-gray-50 p-4 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                  <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de base</h2>
                  <div class="space-y-2">
                    <p><i class="bi bi-geo-alt mr-2"></i><span class="font-medium">Adresse:</span> {{ property.address }}</p>
                    <p><i class="bi bi-currency-dollar mr-2"></i><span class="font-medium">Prix:</span> {{ formatCurrency(property.price) }}</p>
                    <p><i class="bi bi-house mr-2"></i><span class="font-medium">Type:</span> {{ property.property_type }}</p>
                    <p><i class="bi bi-check-circle mr-2"></i><span class="font-medium">Statut:</span>
                      <span :class="property.available_count > 0 ? 'text-green-600' : 'text-red-600'">
                        {{ property.available_count > 0 ? 'Disponible' : 'Indisponible' }}
                      </span>
                    </p>
                  </div>
                </div>

                <!-- Bailleur -->
                <div class="bg-gray-50 p-4 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                  <h2 class="text-xl font-semibold text-gray-900 mb-4">Bailleur</h2>
                  <div class="space-y-2">
                    <p><i class="bi bi-person mr-2"></i><span class="font-medium">Nom:</span> {{ property.landlord.first_name }} {{ property.landlord.last_name }}</p>
                    <p><i class="bi bi-envelope mr-2"></i><span class="font-medium">Email:</span> {{ property.landlord.email }}</p>
                    <p><i class="bi bi-telephone mr-2"></i><span class="font-medium">Téléphone:</span> {{ property.landlord.phone_number }}</p>
                  </div>
                </div>
              </div>

              <!-- Photos -->
              <div v-if="parsedPhotos.length > 0" class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Photos de la propriété</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                  <div v-for="(photo, index) in parsedPhotos" :key="index" class="relative group cursor-pointer" @click="openImageModal(photo)">
                    <img :src="`/storage/${photo}`" :alt="`Photo ${index + 1}`"
                         class="w-full h-48 object-cover rounded-lg shadow-md transition duration-300 ease-in-out transform group-hover:scale-105" />
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                      <i class="bi bi-zoom-in text-white text-3xl"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contrats -->
              <div v-if="property.contracts && property.contracts.length > 0" class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contrats</h2>
                <div class="space-y-4">
                  <div v-for="contract in property.contracts" :key="contract.id"
                       class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                    <p><i class="bi bi-person-check mr-2"></i><span class="font-medium">Locataire:</span> {{ contract.tenant.first_name }} {{ contract.tenant.last_name }}</p>
                    <p><i class="bi bi-cash mr-2"></i><span class="font-medium">Montant du loyer:</span> {{ formatCurrency(contract.rent_amount) }}</p>
                    <p><i class="bi bi-calendar-event mr-2"></i><span class="font-medium">Date de début:</span> {{ formatDate(contract.start_date) }}</p>
                    <p><i class="bi bi-calendar-x mr-2"></i><span class="font-medium">Date de fin:</span> {{ formatDate(contract.end_date) }}</p>
                  </div>
                </div>
              </div>

              <!-- Paiements -->
              <div v-if="property.payments && property.payments.length > 0" class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Paiements précédents</h2>
                <div class="space-y-4">
                  <div v-for="payment in property.payments" :key="payment.id"
                       class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                    <p><i class="bi bi-credit-card mr-2"></i><span class="font-medium">Montant:</span> {{ formatCurrency(payment.amount) }}</p>
                    <p><i class="bi bi-calendar-check mr-2"></i><span class="font-medium">Date:</span> {{ formatDate(payment.payment_date) }}</p>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex justify-end space-x-4 mt-8">
                <button @click="restoreProperty" class="btn-primary">
                  <i class="bi bi-arrow-counterclockwise mr-2"></i>Restaurer
                </button>
                <button @click="openDeleteModal" class="btn-danger">
                  <i class="bi bi-trash mr-2"></i>Supprimer définitivement
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal de confirmation de suppression -->
      <ModalArchive
        v-model:open="showDeleteModal"
        title="Supprimer Définitivement la Propriété"
        :message="`Êtes-vous sûr de vouloir supprimer définitivement la propriété ${property?.name} ? Cette action est irréversible.`"
        status="error"
        @close="closeDeleteModal"
        @confirm="confirmDelete"
      />

      <!-- Modal d'image -->
      <TransitionRoot appear :show="isImageModalOpen" as="template">
        <Dialog as="div" @close="closeImageModal" class="relative z-10">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <div class="fixed inset-0 bg-black bg-opacity-25" />
          </TransitionChild>

          <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
              <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95"
              >
                <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                  <img :src="selectedImage" alt="Image agrandie" class="w-full h-auto" />
                  <button @click="closeImageModal" class="mt-4 inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                    Fermer
                  </button>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>
    </AppLayout>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';
  import { TransitionRoot, TransitionChild, Dialog, DialogPanel } from '@headlessui/vue';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import ModalArchive from '@/Components/Properties/ModalArchive.vue';

  const { props } = usePage();
  const property = ref(props.property);
  const showDeleteModal = ref(false);
  const isImageModalOpen = ref(false);
  const selectedImage = ref('');

  const parsedPhotos = computed(() => {
    return property.value.photos ? JSON.parse(property.value.photos) : [];
  });

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'CFA' }).format(amount);
  };

  const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const openDeleteModal = () => {
    showDeleteModal.value = true;
  };

  const closeDeleteModal = () => {
    showDeleteModal.value = false;
  };

  const confirmDelete = () => {
    if (property.value) {
      router.post(route('properties.forceDelete', property.value.id), {}, {
        onSuccess: () => {
          closeDeleteModal();
          router.get(route('properties.archives'));
        },
        onError: (errors) => {
          console.error(errors);
        }
      });
    }
  };

  const restoreProperty = () => {
    if (property.value) {
      router.post(route('properties.restore', property.value.id), {}, {
        onSuccess: () => {
          router.get(route('properties.index'));
        },
        onError: (errors) => {
          console.error(errors);
        }
      });
    }
  };

  const openImageModal = (photo) => {
    selectedImage.value = `/storage/${photo}`;
    isImageModalOpen.value = true;
  };

  const closeImageModal = () => {
    isImageModalOpen.value = false;
  };
  </script>

  <style scoped>
  @import 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css';

  .btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition-all duration-150 ease-in-out transform hover:scale-105;
  }

  .btn-danger {
    @apply inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition-all duration-150 ease-in-out transform hover:scale-105;
  }
  </style>
