<template>
    <footer class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-16 relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
          <div class="space-y-4">
            <h3 class="text-2xl font-bold">Noflay</h3>
            <p class="text-sm opacity-75">
              Transformez vos idées en réalité avec Noflay. Nous créons des expériences numériques exceptionnelles.
            </p>
          </div>
          <div class="space-y-4">
            <h4 class="text-lg font-semibold">Liens rapides</h4>
            <ul class="space-y-2">
              <li><a href="#accueil" class="hover:text-indigo-200 transition-colors duration-300">Accueil</a></li>
              <li><a href="#fonctionnalites" class="hover:text-indigo-200 transition-colors duration-300">Fonctionnalités</a></li>
              <li><a href="#avantages" class="hover:text-indigo-200 transition-colors duration-300">Avantages</a></li>
              <li><a href="#tarifs" class="hover:text-indigo-200 transition-colors duration-300">Tarifs</a></li>
              <li><a href="#contact" class="hover:text-indigo-200 transition-colors duration-300">Contact</a></li>
            </ul>
          </div>
          <div class="space-y-4">
            <h4 class="text-lg font-semibold">Suivez-nous</h4>
            <div class="flex space-x-4">
              <a href="https://facebook.com/Noflay" class="hover:text-indigo-200 transition-colors duration-300">
                <i class="bi bi-facebook text-2xl"></i>
              </a>
              <a href="https://twitter.com/Noflay" class="hover:text-indigo-200 transition-colors duration-300">
                <i class="bi bi-twitter text-2xl"></i>
              </a>
              <a href="https://instagram.com/Noflay" class="hover:text-indigo-200 transition-colors duration-300">
                <i class="bi bi-instagram text-2xl"></i>
              </a>
              <a href="https://linkedin.com/company/Noflay" class="hover:text-indigo-200 transition-colors duration-300">
                <i class="bi bi-linkedin text-2xl"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="border-t border-white/20 pt-8 flex flex-col md:flex-row items-center justify-between">
          <p class="text-sm opacity-75">&copy; {{ currentYear }} Noflay. Tous droits réservés.</p>
          <div class="flex space-x-6 mt-4 md:mt-0">
            <a href="/privacy-policy" class="text-sm hover:text-indigo-200 transition-colors duration-300">Politique de confidentialité</a>
            <a href="/terms-of-service" class="text-sm hover:text-indigo-200 transition-colors duration-300">Conditions d'utilisation</a>
          </div>
        </div>
      </div>
      <div class="absolute inset-0 z-0 opacity-10">
        <div class="absolute inset-0 bg-repeat bg-center" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
      </div>
      <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none rotate-180">
        <svg class="relative block w-full h-12 text-white" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-current"></path>
        </svg>
      </div>
    </footer>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';

  const currentYear = ref(new Date().getFullYear());

  onMounted(() => {
    const observerOptions = {
      threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-in');
        }
      });
    }, observerOptions);

    document.querySelectorAll('.animate-fade-in-up').forEach(el => observer.observe(el));
  });
  </script>

  <style scoped>
  @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");

  .animate-fade-in-up {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
  }

  .animate-in {
    opacity: 1;
    transform: translateY(0);
  }
  </style>
    