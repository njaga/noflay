<template>
    <div class="fixed inset-0 z-50 overflow-auto bg-smoke-light backdrop-blur-sm flex items-center justify-center">
      <div class="relative p-6 bg-white w-full max-w-md m-auto flex-col flex rounded-xl shadow-2xl">
        <span class="absolute top-2 right-2">
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition duration-200">
            <i class="bi bi-x-lg text-xl"></i>
          </button>
        </span>
        <h2 class="text-2xl font-bold mb-6 text-indigo-800">Enregistrer un paiement</h2>
        <form @submit.prevent="submitPayment" class="space-y-4">
          <div>
            <label class="label" for="payment_method">Méthode de paiement</label>
            <select id="payment_method" v-model="form.payment_method" class="input">
              <option value="cheque">Chèque</option>
              <option value="cash">Espèces</option>
              <option value="cheque_cash">Chèque et Espèces</option>
            </select>
          </div>
          <div v-if="form.payment_method === 'cheque_cash'" class="space-y-4">
            <div>
              <label class="label" for="cheque_number">Numéro de chèque</label>
              <input type="text" id="cheque_number" v-model="form.cheque_number" class="input" required>
            </div>
            <div>
              <label class="label" for="cheque_amount">Montant du chèque</label>
              <input type="number" id="cheque_amount" v-model="form.cheque_amount" class="input" required>
            </div>
            <div>
              <label class="label" for="cash_amount">Montant en espèces</label>
              <input type="number" id="cash_amount" v-model="form.cash_amount" class="input" required>
            </div>
          </div>
          <div v-if="form.payment_method === 'cheque'">
            <label class="label" for="cheque">Numéro de chèque</label>
            <input type="text" id="cheque" v-model="form.cheque_number" class="input" required>
          </div>
          <div>
            <label class="label" for="amount">Montant</label>
            <input type="number" id="amount" v-model="form.amount" class="input" required>
          </div>
          <div>
            <label class="label" for="payout_date">Date de versement</label>
            <input type="date" id="payout_date" v-model="form.payout_date" class="input" required>
          </div>
          <button type="submit" class="btn-primary w-full mt-6">
            <i class="bi bi-check-lg mr-2"></i>
            Enregistrer
          </button>
        </form>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';

  const props = defineProps(['landlord']);
  const form = ref({
    payment_method: '',
    cheque_number: '',
    cheque_amount: '',
    cash_amount: '',
    amount: '',
    payout_date: ''
  });

  const submitPayment = () => {
    form.value.landlord_id = props.landlord.id;
    router.post(route('payments.store'), form.value, {
      onSuccess: () => {
        $emit('close');
        $emit('saved');
      }
    });
  };
  </script>

  <style scoped>
  @import "bootstrap-icons/font/bootstrap-icons.css";

  .bg-smoke-light {
    background: rgba(0, 0, 0, 0.5);
  }

  .btn-primary {
    @apply inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out;
  }

  .input {
    @apply block w-full px-3 py-2 mt-1 text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 ease-in-out;
  }

  .label {
    @apply block text-sm font-medium text-gray-700 mb-1;
  }
  </style>
