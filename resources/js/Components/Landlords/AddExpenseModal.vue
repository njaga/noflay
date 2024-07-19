<template>
    <div class="fixed inset-0 z-50 overflow-auto bg-smoke-light backdrop-blur-sm flex items-center justify-center">
      <div class="relative p-6 bg-white w-full max-w-md m-auto flex-col flex rounded-xl shadow-2xl">
        <span class="absolute top-3 right-3">
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition duration-200">
            <i class="bi bi-x-lg text-xl"></i>
          </button>
        </span>
        <h2 class="text-2xl font-bold mb-6 text-indigo-800">Ajouter une dépense</h2>
        <form @submit.prevent="submitExpense" class="space-y-4">
          <div>
            <label class="label" for="property">Propriété</label>
            <select id="property" v-model="form.property_id" class="input" required>
              <option value="">Sélectionnez une propriété</option>
              <option v-for="property in landlord.properties" :key="property.id" :value="property.id">{{ property.name }}</option>
            </select>
          </div>
          <div>
            <label class="label" for="type">Type</label>
            <input type="text" id="type" v-model="form.type" class="input" required placeholder="Ex: Réparation, Entretien, Taxes">
          </div>
          <div>
            <label class="label" for="description">Description</label>
            <input type="text" id="description" v-model="form.description" class="input" required placeholder="Brève description de la dépense">
          </div>
          <div>
            <label class="label" for="amount">Montant (FCFA)</label>
            <div class="relative">
              <input type="number" id="amount" v-model="form.amount" class="input pr-16" required step="1" min="0" placeholder="0">
              <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">FCFA</span>
            </div>
          </div>
          <div>
            <label class="label" for="expense_date">Date</label>
            <input type="date" id="expense_date" v-model="form.expense_date" class="input" required>
          </div>
          <button type="submit" class="btn-primary w-full mt-6">
            <i class="bi bi-plus-circle mr-2"></i>
            Enregistrer la dépense
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
    property_id: '',
    type: '',
    description: '',
    amount: '',
    expense_date: ''
  });

  const submitExpense = () => {
    // Assurez-vous que le montant est un nombre entier (pas de décimales pour le FCFA)
    form.value.amount = Math.round(Number(form.value.amount));

    router.post(route('expenses.store'), form.value, {
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
