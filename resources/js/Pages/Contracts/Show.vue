<template>
    <AppLayout :title="`Contrat #${contract.id}`">
      <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <ContractHeader :contract="contract" />
          <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
              <ContractDetails
                :contract="contract"
                :totalCommission="totalCommission"
              />
            </div>
            <div>
              <ContractInfoSection
                :generalInfo="generalInfo"
                :financialInfo="financialInfo"
              />
            </div>
          </div>

          <ContractActions
            :contract="contract"
            @edit="editContract"
            @delete="confirmDelete"
          />
        </div>
      </div>

      <Modal :show="showDeleteModal" @close="closeDeleteModal">
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
          <p class="text-sm text-gray-500 mb-4">Êtes-vous sûr de vouloir supprimer ce contrat ? Cette action est irréversible.</p>
          <div class="mt-6 flex justify-end space-x-3">
            <button @click="closeDeleteModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">Annuler</button>
            <button @click="deleteContract" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">Supprimer</button>
          </div>
        </div>
      </Modal>
    </AppLayout>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import Modal from '@/Components/Modal.vue';
  import ContractHeader from '@/Components/Contracts/ContractHeader.vue';
  import ContractDetails from '@/Components/Contracts/ContractDetails.vue';
  import ContractInfoSection from '@/Components/Contracts/ContractInfoSection.vue';
  import ContractActions from '@/Components/Contracts/ContractActions.vue';
  import ContractDocumentsSection from '@/Components/Contracts/ContractDocumentsSection.vue';

  const { props } = usePage();
  const contract = ref(props.contract);
  const totalCommission = ref(props.totalCommission);
  const showDeleteModal = ref(false);

  const confirmDelete = () => {
    showDeleteModal.value = true;
  };

  const closeDeleteModal = () => {
    showDeleteModal.value = false;
  };

  const deleteContract = () => {
    router.delete(route('contracts.destroy', contract.value.id), {
      onSuccess: () => router.visit(route('contracts.index')),
    });
  };

  const editContract = () => {
    router.visit(route('contracts.edit', contract.value.id));
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

  const calculateDuration = (start, end) => {
    const startDate = new Date(start);
    const endDate = new Date(end);
    const diffTime = Math.abs(endDate - startDate);
    const diffMonths = Math.ceil(diffTime / (1000 * 60 * 60 * 24 * 30));
    return diffMonths;
  };

  const generalInfo = computed(() => [
    {
      label: 'Date de début',
      value: formatDate(contract.value.start_date),
      icon: 'calendar',
    },
    {
      label: 'Date de fin',
      value: formatDate(contract.value.end_date),
      icon: 'calendar-check',
    },
    {
      label: 'Durée',
      value: `${calculateDuration(contract.value.start_date, contract.value.end_date)} mois`,
      icon: 'clock',
    },
    {
      label: 'Numéro de dossier',
      value: contract.value.file_number,
      icon: 'folder',
    },
  ]);

  const financialInfo = computed(() => [
    {
      label: 'Montant du loyer',
      value: formatCurrency(contract.value.rent_amount),
      icon: 'cash',
    },
    {
      label: 'Caution',
      value: formatCurrency(contract.value.caution_amount),
      icon: 'shield',
    },
    {
      label: 'Commission totale',
      value: formatCurrency(totalCommission.value),
      icon: 'graph-up',
    },
  ]);
  </script>
