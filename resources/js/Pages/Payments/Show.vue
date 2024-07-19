<template>
    <AppLayout title="Détails du Paiement">
      <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
          <h2 class="text-3xl font-bold text-indigo-700 mb-6">Détails du Paiement</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">ID du Paiement</label>
              <div class="mt-1 text-lg text-gray-900">{{ payment.id }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Nom du Locataire</label>
              <div class="mt-1 text-lg text-gray-900">{{ tenant.first_name }} {{ tenant.last_name }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Propriété</label>
              <div class="mt-1 text-lg text-gray-900">{{ property.name }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Montant</label>
              <div class="mt-1 text-lg text-gray-900">{{ payment.amount }} CFA</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Date de Paiement</label>
              <div class="mt-1 text-lg text-gray-900">{{ formatDate(payment.payment_date) }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Contrat ID</label>
              <div class="mt-1 text-lg text-gray-900">{{ contract.id }}</div>
            </div>
            <PaymentReceipt :payment="payment" :contract="contract" :tenant="tenant" :property="property" />
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import PaymentReceipt from '@/Components/PaymentReceipt.vue';

  const { props } = usePage();
  const payment = ref(props.payment);
  const contract = ref(props.contract);
  const tenant = ref(props.tenant);
  const property = ref(props.property);

  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', options);
  };
  </script>
