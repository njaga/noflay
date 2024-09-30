<template>
    <nav class="bg-white fixed w-full z-50 transition-all duration-300" :class="{ 'bg-opacity-95 shadow-lg': scrolled }">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <div class="flex items-center justify-between w-full">
            <div class="shrink-0 flex items-center">
              <Link :href="route('welcome')">
                <ApplicationLogo class="block h-10 w-auto" />
              </Link>
            </div>
            <div class="hidden md:flex items-center space-x-1">
              <Link
                v-for="item in navigationItems"
                :key="item.name"
                :href="item.href"
                class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 ease-in-out relative group"
                :class="[
                  item.current
                    ? 'text-indigo-600 bg-indigo-50'
                    : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50'
                ]"
              >
                {{ item.name }}
                <span
                  class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200 ease-out"
                  :class="{ 'scale-x-100': item.current }"
                ></span>
              </Link>
            </div>
          </div>
          <div class="hidden md:block">
            <button @click="emitLoginModalEvent"
              class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-md transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
              Connexion
            </button>
          </div>
          <div class="md:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen"
              class="inline-flex items-center justify-center p-2 rounded-md text-indigo-600 hover:text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-all duration-200 ease-in-out"
            >
              <span class="sr-only">Ouvrir le menu principal</span>
              <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg class="h-6 w-6" :class="{ 'hidden': !mobileMenuOpen, 'block': mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div v-show="mobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg">
          <Link
            v-for="item in navigationItems"
            :key="item.name"
            :href="item.href"
            class="block px-3 py-2 rounded-md text-base font-medium transition-all duration-200 ease-in-out"
            :class="[
              item.current
                ? 'text-indigo-600 bg-indigo-50'
                : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50'
            ]"
            @click="mobileMenuOpen = false"
          >
            {{ item.name }}
          </Link>
          <div class="mt-4 pt-4 border-t border-gray-200">
            <button @click="emitLoginModalEvent(); mobileMenuOpen = false"
              class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
              Connexion
            </button>
          </div>
        </div>
      </div>
    </nav>
  </template>

  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue';
  import { Link } from '@inertiajs/vue3';
  import ApplicationMark from '@/Components/ApplicationMark.vue';
  import ApplicationLogo from './ApplicationLogo.vue';

  const navigationItems = ref([
    { name: 'Accueil', href: '/', current: true },
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
    scrolled.value = window.scrollY > 20;
  };

  onMounted(() => {
    window.addEventListener('scroll', handleScroll);
  });

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });
  </script>
