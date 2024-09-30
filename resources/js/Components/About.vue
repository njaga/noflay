<template>
    <section id="about" class="py-24 bg-gradient-to-b from-white to-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-20 text-center">
          <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
            Notre Vision, <span class="text-indigo-600">Votre Succès</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Noflay est une plateforme intuitive qui permet aux professionnels de l'immobilier d'exceller dans leur travail, d'améliorer l'efficacité opérationnelle et la satisfaction client.
          </p>
        </div>

        <div class="grid gap-16 lg:grid-cols-2 items-start">
          <div class="space-y-12">
            <div v-for="(value, index) in values" :key="index"
                 class="flex items-start space-x-6 bg-white p-6 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600">
                  <component :is="value.icon" class="w-8 h-8" />
                </div>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                  {{ value.title }}
                </h3>
                <p class="text-gray-600">
                  {{ value.description }}
                </p>
              </div>
            </div>
          </div>

          <div class="relative">
            <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-xl overflow-hidden shadow-2xl">
              <img src="/img/noflay-og-image.jpg.jpeg" alt="NOFLAY en action" class="object-cover w-full h-full" />
              <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-20"></div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-indigo-600 rounded-lg"></div>
            <div class="absolute -top-6 -left-6 w-24 h-24 border-4 border-indigo-600 rounded-lg"></div>
          </div>
        </div>
      </div>
    </section>
  </template>

  <script setup>
  import { ref } from "vue";
  import { LightBulbIcon, ShieldCheckIcon, UserGroupIcon } from '@heroicons/vue/24/outline';

  const values = ref([
    {
      icon: LightBulbIcon,
      title: "Innovation",
      description: "Nous repoussons constamment les limites de la technologie pour offrir des solutions avant-gardistes."
    },
    {
      icon: ShieldCheckIcon,
      title: "Fiabilité",
      description: "La confiance est au cœur de notre service. Nous offrons une plateforme stable, sécurisée et performante."
    },
    {
      icon: UserGroupIcon,
      title: "Engagement client",
      description: "Votre réussite est notre priorité absolue. Nous sommes dédiés à votre croissance et votre satisfaction."
    }
  ]);
  </script>

  <style scoped>
  /* Vous pouvez ajouter des styles spécifiques ici si nécessaire */
  </style>
