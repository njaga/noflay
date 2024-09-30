<template>
    <section id="faq" class="relative overflow-hidden bg-gradient-to-b from-white to-indigo-50 py-24">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
          <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl mb-6">
            Questions fréquemment posées sur <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-400">Noflay</span>
          </h2>
          <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
            Trouvez rapidement des réponses à vos questions sur notre service de gestion immobilière innovant.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div v-for="(faq, index) in faqs" :key="index"
               class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg border border-indigo-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="p-6">
              <div class="flex justify-between items-center cursor-pointer" @click="toggleFaq(index)">
                <h3 class="text-xl font-semibold text-gray-800">{{ faq.question }}</h3>
                <span class="text-indigo-600 transition-transform duration-300" :class="{ 'transform rotate-45': faq.isOpen }">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </span>
              </div>
              <p class="mt-4 text-gray-600 leading-relaxed" v-show="faq.isOpen">{{ faq.answer }}</p>
            </div>
          </div>
        </div>

        <div class="mt-16 text-center">
          <p class="text-xl text-gray-600">
            Vous ne trouvez pas la réponse que vous cherchez ?
          </p>
          <a href="#contact" class="inline-block mt-4 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-300">
            Contactez notre support
          </a>
        </div>
      </div>
    </section>
  </template>

  <script setup>
  import { ref } from 'vue';

  const faqs = ref([
    {
      question: "Quelles sont les principales fonctionnalités de Noflay ?",
      answer: "Noflay offre une gestion complète incluant : gestion des utilisateurs et des rôles, suivi des propriétés et des contrats, facturation et paiements, traitement des mandats de gérance, rapports d'activité, notifications en temps réel, gestion des locataires et des documents.",
      isOpen: false
    },
    {
      question: "Comment Noflay gère-t-il la sécurité des données ?",
      answer: "Nous utilisons des protocoles de cryptage avancés, l'authentification multi-facteurs, et des pratiques de sécurité conformes aux normes de l'industrie pour protéger toutes les données de nos utilisateurs.",
      isOpen: false
    },
    {
      question: "Est-il possible de personnaliser les rapports d'activité ?",
      answer: "Oui, notre plateforme permet de personnaliser les rapports d'activité selon vos besoins spécifiques, vous permettant de suivre les métriques qui comptent le plus pour votre entreprise.",
      isOpen: false
    },
    {
      question: "Comment fonctionne le système de notifications de Noflay ?",
      answer: "Notre système de notifications en temps réel vous alerte sur les événements importants tels que les paiements reçus, les contrats arrivant à échéance, ou les demandes de maintenance, vous permettant de rester toujours informé.",
      isOpen: false
    },
    {
      question: "Noflay gère-t-il les mandats de gérance ?",
      answer: "Absolument, Noflay permet de gérer l'ensemble du processus des mandats de gérance, de la création à la signature, en passant par le suivi et le renouvellement.",
      isOpen: false
    },
    {
      question: "Offrez-vous une formation pour utiliser Noflay ?",
      answer: "Oui, nous proposons des sessions de formation complètes ainsi que des ressources en ligne pour vous aider à tirer le meilleur parti de Noflay. Notre équipe de support est également disponible pour répondre à vos questions.",
      isOpen: false
    }
  ]);

  const toggleFaq = (index) => {
    faqs.value[index].isOpen = !faqs.value[index].isOpen;
  };
  </script>
