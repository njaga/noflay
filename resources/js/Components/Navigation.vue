<template>
    <nav class="bg-white fixed w-full z-50 transition-all duration-300" :class="{ 'bg-opacity-90 shadow-md': scrolled }">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center justify-center w-full">
            <div class="shrink-0 flex items-center">
              <Link :href="route('welcome')">
                <ApplicationMark class="block h-9 w-auto text-indigo-600" />
              </Link>
            </div>
            <div class="hidden md:block mx-auto">
              <div class="flex items-baseline space-x-4">
                <a v-for="item in navigationItems" :key="item.name" :href="item.href"
                  class="text-indigo-600 hover:bg-indigo-500 hover:text-white hover:bg-opacity-75 px-3 py-2 rounded-md text-sm font-medium"
                  >
                  {{ item.name }}
                </a>
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <button @click="emitLoginModalEvent"
              class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:scale-105">
              Connexion
            </button>
          </div>
          <div class="-mr-2 flex md:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen"
              class="bg-indigo-600 inline-flex items-center justify-center p-2 rounded-md text-indigo-200 hover:text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white">
              <span class="sr-only">Open main menu</span>
              <i class="bi" :class="mobileMenuOpen ? 'bi-x' : 'bi-list'"></i>
            </button>
          </div>
        </div>
      </div>

      <div v-show="mobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <a v-for="item in navigationItems" :key="item.name" :href="item.href"
            class="text-indigo-600 hover:bg-indigo-500 hover:bg-opacity-75 block px-3 py-2 rounded-md text-base font-medium"
            :class="{ 'bg-indigo-700': item.current }">
            {{ item.name }}
          </a>
        </div>
        <div class="pt-4 pb-3 border-t border-indigo-700">
          <div class="flex items-center px-5">
            <button @click="emitLoginModalEvent"
              class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:scale-105 w-full">
              Connexion
            </button>
          </div>
        </div>
      </div>
    </nav>
  </template>

  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue';
  import { Link, usePage } from '@inertiajs/vue3';
  import ApplicationMark from '@/Components/ApplicationMark.vue';

  const { props } = usePage();
  const navigationItems = ref([
    { name: 'Accueil', href: '#accueil', current: true },
    { name: 'Fonctionnalités', href: '#fonctionnalites', current: false },
    { name: 'Avantages', href: '#avantages', current: false },
    { name: 'Tarifs', href: '#tarifs', current: false },
    { name: 'Contact', href: '#contact', current: false },
  ]);

  const mobileMenuOpen = ref(false);
  const scrolled = ref(false);

  const emitLoginModalEvent = () => {
    window.dispatchEvent(new CustomEvent('open-login-modal'));
  };

  const handleScroll = () => {
    scrolled.value = window.scrollY > 50;
  };

  onMounted(() => {
    window.addEventListener('scroll', handleScroll);
  });

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });
  </script>

  <style scoped>
  /* Ajoutez vos styles personnalisés ici */
  </style>
