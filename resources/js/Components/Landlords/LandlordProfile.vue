<template>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
      <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-20 h-20 rounded-full bg-white text-indigo-600 flex items-center justify-center text-3xl font-bold">
              {{ getInitials(landlord.first_name, landlord.last_name) }}
            </div>
            <div>
              <h2 class="text-3xl font-bold text-white">
                {{ landlord.first_name }} {{ landlord.last_name }}
              </h2>
              <p class="text-indigo-200">Bailleur depuis {{ formatDate(landlord.created_at) }}</p>
            </div>
          </div>
          <div class="flex space-x-4">
            <button @click="$emit('open-generate-mandat')" class="btn-primary">
              <i class="bi bi-file-earmark-text mr-2"></i> Mandat de Gérance
            </button>
            <button @click="$emit('edit-landlord')" class="btn-secondary">
              <i class="bi bi-pencil mr-2"></i> Modifier
            </button>
            <button @click="$emit('confirm-delete-landlord')" class="btn-danger">
              <i class="bi bi-trash mr-2"></i> Supprimer
            </button>
          </div>
        </div>
      </div>

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

        <div class="mt-2">
          <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations personnelles</h3>
          <p class="flex items-center mb-2">
            <i class="bi bi-person-vcard mr-2 text-indigo-600"></i> Numéro CNI: {{ landlord.identity_number }}
          </p>
          <p class="flex items-center mb-2">
            <i class="bi bi-hourglass-split mr-2 text-indigo-600"></i> Expire le: {{ formatDate(landlord.identity_expiry_date) }}
          </p>
        </div>

        <div class="mt-2">
          <h3 class="text-xl font-semibold text-gray-800 mb-4">Pièces jointes</h3>
          <div v-if="attachments.length > 0" class="space-y-2">
            <div v-for="(attachment, index) in attachments" :key="index" class="flex items-center space-x-2">
              <i class="bi bi-paperclip text-indigo-600"></i>
              <a :href="attachment" target="_blank" class="text-indigo-600 hover:underline">Pièce jointe {{ index + 1 }}</a>
            </div>
          </div>
          <div v-else class="text-gray-500">
            Aucune pièce jointe disponible.
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  const props = defineProps({
    landlord: Object,
    attachments: Array
  });

  function formatDate(dateString) {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
  }

  function getInitials(firstName, lastName) {
    return (firstName[0] + lastName[0]).toUpperCase();
  }
  </script>

  <style scoped>
  @import "bootstrap-icons/font/bootstrap-icons.css";

  .btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 text-white border-2 border-white rounded-md font-semibold text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out transform hover:-translate-y-1;
  }

  .btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white text-indigo-600 border-2 border-indigo-600 rounded-md font-semibold text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out transform hover:-translate-y-1;
  }

  .btn-primary:hover {
    @apply bg-white text-indigo-600 border-2 border-indigo-600;
  }

  .btn-secondary:hover {
    @apply bg-indigo-600 text-white border-2 border-white;
  }

  .btn-danger {
    @apply inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 ease-in-out transform hover:-translate-y-1;
  }

  .btn-outline {
    @apply inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md font-semibold text-sm shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out;
  }
  </style>
