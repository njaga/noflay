<template>
    <button @click="exportToExcel" :disabled="isLoading" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        <i class="bi bi-file-earmark-spreadsheet"></i>
      {{ isLoading ? 'Exportation en cours...' : 'Exporter en Excel' }}
    </button>
  </template>

  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import * as XLSX from 'xlsx';

  const props = defineProps({
    filters: {
      type: Object,
      default: () => ({})
    }
  });

  const isLoading = ref(false);

  const exportToExcel = async () => {
    isLoading.value = true;
    try {
      console.log('Filtres envoyés:', props.filters);

      const response = await axios.get('/api/transactions/export', {
        params: props.filters,
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      console.log('Réponse brute:', response);
      console.log('Données reçues:', response.data);

      const data = response.data;

      if (!Array.isArray(data)) {
        console.error('Les données reçues ne sont pas un tableau:', data);
        throw new Error('Format de données invalide reçu du serveur');
      }

      if (data.length === 0) {
        alert('Aucune donnée à exporter. Veuillez ajuster vos filtres et réessayer.');
        return;
      }

      // Créer un nouveau classeur
      const workbook = XLSX.utils.book_new();

      // Convertir les données en une feuille de calcul
      const worksheet = XLSX.utils.json_to_sheet(data);

      // Ajouter la feuille de calcul au classeur
      XLSX.utils.book_append_sheet(workbook, worksheet, "Transactions");

      // Générer le fichier Excel
      XLSX.writeFile(workbook, "transactions.xlsx");

      console.log('Export terminé avec succès');
    } catch (err) {
      console.error('Erreur détaillée lors de l\'exportation:', err);
      if (err.response) {
        console.error('Données de la réponse d\'erreur:', err.response.data);
        console.error('Statut de l\'erreur:', err.response.status);
        console.error('En-têtes de l\'erreur:', err.response.headers);
      }
      alert('Une erreur est survenue lors de l\'exportation: ' + (err.response?.data?.error || err.message));
    } finally {
      isLoading.value = false;
    }
  };
  </script>
