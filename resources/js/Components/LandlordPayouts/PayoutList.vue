<!-- Components/LandlordPayouts/PayoutList.vue -->
<template>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th v-for="header in tableHeaders" :key="header.key" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{ header.label }}
            </th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="payout in payouts" :key="payout.id">
            <td v-for="header in tableHeaders" :key="header.key" class="px-6 py-4 whitespace-nowrap">
              <div v-if="header.key === 'landlord'" class="flex items-center">
                <span
                  v-if="payout.landlord"
                  class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-inner mr-2"
                >
                  {{ payout.landlord && payout.landlord.first_name ? payout.landlord.first_name[0] : '' }}{{ payout.landlord && payout.landlord.last_name ? payout.landlord.last_name[0] : '' }}
                </span>
                <div v-if="payout.landlord">
                  {{ payout.landlord ? `${payout.landlord.first_name} ${payout.landlord.last_name}` : 'N/A' }}
                </div>
                <div v-else>
                  N/A
                </div>
              </div>
              <div v-else-if="header.key === 'amount'" class="font-semibold text-indigo-600">
                {{ formatCurrency(payout[header.key]) }}
              </div>
              <div v-else-if="header.key === 'transaction_date'">
                {{ formatDate(payout[header.key]) }}
              </div>
              <div v-else>
                {{ payout[header.key] }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button @click="$emit('view', payout)" class="text-blue-600 hover:text-blue-800 mr-2">
                <i class="bi bi-eye-fill"></i>
              </button>
              <button @click="$emit('edit', payout)" class="text-indigo-600 hover:text-indigo-800 mr-2">
                <i class="bi bi-pencil-fill"></i>
              </button>
              <button @click="$emit('delete', payout)" class="text-red-600 hover:text-red-800">
                <i class="bi bi-trash-fill"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script setup>
  import { defineProps} from 'vue';

  const props = defineProps({
    payouts: {
      type: Array,
      required: true
    },
    tableHeaders: {
      type: Array,
      required: true
    }
  });

  defineEmits(['view', 'edit', 'delete']);

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: "XOF",
    }).format(amount);
  };

  const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
  };
  </script>
