<template>
    <AppLayout title="Modifier le Paiement">
      <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
          <h2 class="text-3xl font-bold text-indigo-700 mb-6">Modifier le Paiement</h2>
          <form @submit.prevent="submit" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="tenant_id" class="block text-sm font-medium text-gray-700">Locataire</label>
                <select id="tenant_id" v-model="form.tenant_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                  <option value="" disabled>Sélectionnez un locataire</option>
                  <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                    {{ tenant.first_name }} {{ tenant.last_name }}
                  </option>
                </select>
              </div>
              <div>
                <label for="contract_id" class="block text-sm font-medium text-gray-700">Contrat</label>
                <select id="contract_id" v-model="form.contract_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                  <option value="" disabled>Sélectionnez un contrat</option>
                  <option v-for="contract in contracts" :key="contract.id" :value="contract.id">
                    {{ contract.file_number }}
                  </option>
                </select>
              </div>
              <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
                <input type="number" id="amount" v-model="form.amount" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required />
              </div>
              <div>
                <label for="payment_date" class="block text-sm font-medium text-gray-700">Date de Paiement</label>
                <input type="date" id="payment_date" v-model="form.payment_date" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required />
              </div>
            </div>
            <div class="mt-8 flex justify-end">
              <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                Enregistrer les modifications
              </button>
            </div>
          </form>
          <Modal :show="showSuccessModal" @close="closeSuccessModal">
            <div class="p-6 bg-white rounded-lg shadow-xl">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Modification effectuée avec succès</h3>
              <div class="mt-6 flex justify-end">
                <button @click="closeSuccessModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                  OK
                </button>
              </div>
            </div>
          </Modal>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, reactive, onMounted } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import Modal from '@/Components/Modal.vue';

  const { props } = usePage();
  const payment = ref(props.payment);
  const tenants = ref(props.tenants || []);
  const contracts = ref(props.contracts || []);

  const form = reactive({
    tenant_id: payment.value.tenant_id,
    contract_id: payment.value.contract_id,
    amount: payment.value.amount,
    payment_date: payment.value.payment_date,
  });

  const showSuccessModal = ref(false);

  const submit = () => {
    router.put(route('payments.update', payment.value.id), form, {
      onSuccess: () => {
        showSuccessModal.value = true;
      },
    });
  };

  const closeSuccessModal = () => {
    showSuccessModal.value = false;
  };
  </script>
