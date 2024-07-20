<!-- Components/LandlordPayouts/NewPayoutModal.vue -->
<template>
    <Modal :show="show" @close="$emit('close')">
      <div class="p-6 sm:p-10">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
          <i class="bi bi-plus-circle-fill text-indigo-500 mr-3"></i>
          Nouveau versement
        </h3>
        <form @submit.prevent="$emit('submit', newPayout)">
          <div class="space-y-4">
            <div>
              <label for="landlord" class="block text-sm font-medium text-gray-700">Bailleur</label>
              <select
                id="landlord"
                v-model="newPayout.landlord_id"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
                  {{ landlord.first_name }} {{ landlord.last_name }}
                </option>
              </select>
            </div>
            <div>
              <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
              <input
                type="number"
                id="amount"
                v-model="newPayout.amount"
                min="0"
                step="0.01"
                required
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              />
            </div>
            <div>
              <label for="payment_date" class="block text-sm font-medium text-gray-700">Date de paiement</label>
              <input
                type="date"
                id="payment_date"
                v-model="newPayout.payment_date"
                required
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              />
            </div>
            <div>
              <label for="payment_method" class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
              <select
                id="payment_method"
                v-model="newPayout.payment_method"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="bank">Virement bancaire</option>
                <option value="cash">Espèces</option>
                <option value="cheque">Chèque</option>
              </select>
            </div>
          </div>
          <button type="submit" class="mt-6 w-full px-6 py-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center">
            <i class="bi bi-check-lg mr-2"></i>
            Créer le versement
          </button>
        </form>
      </div>
    </Modal>
  </template>

  <script setup>
  import { defineProps, defineEmits, ref } from 'vue';
  import Modal from "@/Components/Modal.vue";

  const props = defineProps({
    show: {
      type: Boolean,
      required: true
    },
    landlords: {
      type: Array,
      required: true
    }
  });

  const newPayout = ref({
    landlord_id: "",
    amount: 0,
    payment_date: "",
    payment_method: "bank",
  });

  defineEmits(['close', 'submit']);
  </script>
