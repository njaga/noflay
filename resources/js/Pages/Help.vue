<template>
    <AppLayout title="Aide">
      <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <h1 class="text-4xl font-extrabold text-indigo-900 mb-8 text-center">Centre d'Aide</h1>

          <!-- Barre de recherche -->
          <div class="mb-8 relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher une question..."
              class="w-full p-4 pr-12 text-indigo-900 border border-indigo-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
            <span class="absolute right-4 top-4 text-indigo-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </span>
          </div>

          <!-- Liste des FAQs -->
          <div class="space-y-6">
            <div v-for="(faq, index) in filteredFaqs" :key="index" class="bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
              <div
                @click="toggleFaq(index)"
                class="p-6 cursor-pointer flex justify-between items-center"
                :class="{ 'bg-indigo-100': openedFaq === index }"
              >
                <h2 class="text-xl font-semibold text-indigo-800">{{ faq.question }}</h2>
                <span class="text-indigo-600">
                  <svg v-if="openedFaq !== index" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                  </svg>
                </span>
              </div>
              <div v-show="openedFaq === index" class="p-6 bg-indigo-50">
                <p class="text-indigo-700">{{ faq.answer }}</p>
              </div>
            </div>
          </div>

          <!-- Message si aucun résultat -->
          <div v-if="filteredFaqs.length === 0" class="mt-8 text-center">
            <p class="text-xl text-indigo-800">Aucun résultat trouvé pour votre recherche.</p>
            <p class="mt-2 text-indigo-600">Essayez de reformuler votre question ou contactez notre support.</p>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import AppLayout from '@/Layouts/AppLayout.vue';

  const faqs = ref([
    {
      question: "Comment créer un compte entreprise ?",
      answer: "Pour créer un compte entreprise, allez dans la section 'Comptes Entreprises' du tableau de bord, cliquez sur 'Créer un compte' et remplissez les informations nécessaires."
    },
    {
      question: "Comment ajouter un locataire ?",
      answer: "Pour ajouter un locataire, allez dans la section 'Locataires' du tableau de bord, cliquez sur 'Ajouter un locataire' et remplissez les informations requises."
    },
    {
      question: "Comment générer une facture ?",
      answer: "Pour générer une facture, allez dans la section 'Factures' du tableau de bord, cliquez sur 'Créer une facture' et sélectionnez les détails du contrat et les montants."
    },
    {
      question: "Comment restaurer un locataire supprimé ?",
      answer: "Pour restaurer un locataire supprimé, allez dans la section 'Archives' des locataires, trouvez le locataire que vous souhaitez restaurer et cliquez sur 'Restaurer'."
    },
    {
      question: "Comment supprimer définitivement un bailleur ?",
      answer: "Pour supprimer définitivement un bailleur, allez dans la section 'Archives' des bailleurs, trouvez le bailleur que vous souhaitez supprimer et cliquez sur 'Supprimer définitivement'."
    }
  ]);

  const searchQuery = ref('');
  const openedFaq = ref(null);

  const filteredFaqs = computed(() => {
    if (searchQuery.value === '') return faqs.value;
    return faqs.value.filter(faq =>
      faq.question.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      faq.answer.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });

  const toggleFaq = (index) => {
    openedFaq.value = openedFaq.value === index ? null : index;
  };
  </script>

  <style scoped>
  /* Vous pouvez ajouter des styles spécifiques ici si nécessaire */
  </style>
