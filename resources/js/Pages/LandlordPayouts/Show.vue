<template>
    <AppLayout :title="`Versement #${payout.id}`">
      <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Payout Header -->
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-8">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                  <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-md">
                    <i class="bi-currency-exchange text-indigo-600 text-4xl"></i>
                  </div>
                  <div>
                    <h2 class="text-4xl font-bold text-white">Versement N°{{ payout.id }}</h2>
                    <p class="text-blue-100 text-lg">{{ formatDate(payout.payout_date) }}</p>
                  </div>
                </div>
                <span :class="[
                  'px-4 py-2 text-sm font-semibold rounded-full shadow-sm',
                  payout.status === 'Completed' ? 'bg-green-400 text-green-900' : 'bg-yellow-400 text-yellow-900'
                ]">
                  {{ payout.status }}
                </span>
              </div>
            </div>
          </div>

          <!-- Payout Information -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <div v-for="(infoSet, index) in [generalInfo, financialInfo, landlordInfo]" :key="index"
                 class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-105">
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                <h3 class="text-xl font-semibold text-blue-900 flex items-center">
                  <i :class="['mr-3 text-2xl', infoSetIcons[index]]"></i>
                  {{ infoSetTitles[index] }}
                </h3>
              </div>
              <div class="p-6">
                <dl class="space-y-4">
                  <div v-for="item in infoSet" :key="item.label" class="flex items-center justify-between">
                    <dt class="text-sm font-medium text-gray-600 flex items-center">
                      <i :class="['mr-2 text-blue-500', item.icon]"></i>
                      {{ item.label }}
                    </dt>
                    <dd :class="['text-sm',
                                 item.highlight ? 'font-bold text-indigo-600' : 'text-gray-900']">
                      {{ item.value }}
                    </dd>
                  </div>
                </dl>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end space-x-4">
            <a :href="route('landlord-payouts.edit', payout.id)"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300">
              <i class="bi-pencil mr-2"></i>
              Modifier
            </a>
            <button @click="confirmDelete"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-300">
              <i class="bi-trash mr-2"></i>
              Supprimer le versement
            </button>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                  <i class="bi-exclamation-triangle text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    Confirmer la suppression
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      Êtes-vous sûr de vouloir supprimer ce versement ? Cette action est irréversible.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button type="button" @click="deletePayout"
                      class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-300">
                Supprimer
              </button>
              <button type="button" @click="closeDeleteModal"
                      class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-300">
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';

  const { payout, transactions } = usePage().props;

  const showDeleteModal = ref(false);

  const confirmDelete = () => {
    showDeleteModal.value = true;
  };

  const closeDeleteModal = () => {
    showDeleteModal.value = false;
  };

  const deletePayout = () => {
    router.delete(route('landlord-payouts.destroy', payout.id), {
      preserveState: false,
      preserveScroll: true,
      onSuccess: () => router.visit(route('landlord-payouts.index')),
    });
  };

  const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'XOF',
    }).format(amount);
  };

  const generalInfo = computed(() => [
    { label: 'ID du versement', value: payout.id, icon: 'bi-file-earmark' },
    { label: 'Date du versement', value: formatDate(payout.payout_date), icon: 'bi-calendar-event' },
    { label: 'Description', value: payout.description, icon: 'bi-info-circle' },
  ]);

  const financialInfo = computed(() => [
    { label: 'Montant', value: formatCurrency(payout.amount), icon: 'bi-cash', highlight: true },
    { label: 'Commission', value: formatCurrency(payout.commission_amount), icon: 'bi-percent' },
    { label: 'Montant net', value: formatCurrency(payout.net_amount), icon: 'bi-wallet' },
  ]);

  const landlordInfo = computed(() => [
    { label: 'Nom', value: `${payout.landlord.first_name} ${payout.landlord.last_name}`, icon: 'bi-person' },
    { label: 'Email', value: payout.landlord.email, icon: 'bi-envelope' },
    { label: 'Téléphone', value: payout.landlord.phone, icon: 'bi-phone' },
  ]);

  const infoSetTitles = ['Informations générales', 'Détails financiers', 'Informations du bailleur'];
  const infoSetIcons = ['bi-info-circle', 'bi-currency-dollar', 'bi-person'];
  </script>

  <style scoped>
  @import "bootstrap-icons/font/bootstrap-icons.css";
  </style>
