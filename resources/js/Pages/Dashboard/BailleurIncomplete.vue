<template>
    <AppLayout title="Profil Bailleur Incomplet">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <h2 class="text-2xl font-semibold mb-4">Profil Bailleur Incomplet</h2>
              <p class="mb-4">{{ message }}</p>
              <p class="mb-4">Veuillez compléter votre profil de bailleur pour accéder à votre tableau de bord.</p>
              <Link :href="route('landlord.profile.show')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                Compléter mon profil
              </Link>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { Link } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';

  defineProps({
    message: String,
    user: Object
  });
  </script>
