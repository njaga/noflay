<template>
    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Coordonnées</h3>
        <p class="flex items-center mb-2">
          <i class="bi bi-envelope mr-2 text-indigo-600"></i> {{ landlord.email }}
        </p>
        <p class="flex items-center mb-2">
          <i class="bi bi-telephone mr-2 text-indigo-600"></i> {{ landlord.phone }}
        </p>
        <p class="flex items-center">
          <i class="bi bi-geo-alt mr-2 text-indigo-600"></i> {{ landlord.address }}
        </p>
      </div>
      <div>
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Détails du compte</h3>
        <p class="flex items-center mb-2">
          <i class="bi bi-percent mr-2 text-indigo-600"></i> Commission: {{ landlord.agency_percentage }}%
        </p>
        <p class="flex items-center mb-2">
          <i class="bi bi-calendar-date mr-2 text-indigo-600"></i> Inscrit le: {{ formatDate(landlord.created_at) }}
        </p>
        <p class="flex items-center">
          <i class="bi bi-hourglass-split mr-2 text-indigo-600"></i> Durée du contrat: {{ landlord.contract_duration }} mois
        </p>
      </div>
      <div>
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions rapides</h3>
        <div class="flex flex-col space-y-2">
          <button @click="$emit('open-add-expense-modal')" class="btn-outline">
            <i class="bi bi-plus-circle mr-2"></i> Ajouter une dépense
          </button>
          <button @click="$emit('open-add-payment-modal')" class="btn-outline">
            <i class="bi bi-cash-coin mr-2"></i> Enregistrer un paiement
          </button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  const props = defineProps(['landlord']);

  const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
  };
  </script>

  <style scoped>
  @import "bootstrap-icons/font/bootstrap-icons.css";
  </style>
