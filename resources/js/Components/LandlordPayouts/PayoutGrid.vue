<!-- Components/LandlordPayouts/PayoutGrid.vue -->
<template>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
      <div
        v-for="payout in payouts"
        :key="payout.id"
        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden border border-gray-100"
      >
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <div class="flex items-center">
              <span
                class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-inner"
              >
                {{ payout.landlord && payout.landlord.first_name ? payout.landlord.first_name[0] : '' }}{{ payout.landlord && payout.landlord.last_name ? payout.landlord.last_name[0] : '' }}
              </span>
              <div class="ml-4">
                <div class="font-semibold text-gray-800">
                  {{ payout.landlord ? `${payout.landlord.first_name} ${payout.landlord.last_name}` : 'N/A' }}
                </div>
                <div class="text-sm text-gray-500 flex items-center mt-1">
                  <i class="bi bi-calendar3 mr-1"></i>
                  {{ formatDate(payout.transaction_date) }}
                </div>
              </div>
            </div>
          </div>
          <div class="mt-4">
            <div class="text-2xl font-bold text-indigo-600">{{ formatCurrency(payout.amount) }}</div>
          </div>
          <div class="mt-6 flex justify-end space-x-2">
            <button
              @click="$emit('view', payout)"
              class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
            >
              <i class="bi bi-eye-fill"></i>
            </button>
            <button
              @click="$emit('edit', payout)"
              class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200"
            >
              <i class="bi bi-pencil-fill"></i>
            </button>
            <button
              @click="$emit('delete', payout)"
              class="text-red-600 hover:text-red-800 transition-colors duration-200"
            >
              <i class="bi bi-trash-fill"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>


  const props = defineProps({
    payouts: {
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
