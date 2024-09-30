<template>
    <section id="avantages" class="relative overflow-hidden bg-gradient-to-b from-indigo-50 to-white py-24">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
          <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl mb-6">
            Découvrez l'avenir de la gestion immobilière avec <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-400">Noflay</span>
          </h2>
          <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
            Simplifiez votre gestion, sécurisez vos données et adaptez la plateforme à tous vos besoins immobiliers.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="(feature, index) in features" :key="feature.name"
               class="relative group overflow-hidden rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-indigo-400 opacity-75 transition-all duration-300 group-hover:opacity-100"></div>
            <div class="relative p-8 h-full flex flex-col justify-between z-10">
              <div>
                <component :is="feature.icon" class="h-12 w-12 text-white mb-6" aria-hidden="true" />
                <h3 class="text-2xl font-bold text-white mb-4">{{ feature.name }}</h3>
                <p class="text-gray-100 leading-relaxed">{{ feature.description }}</p>
              </div>
              <p class="mt-6 text-white font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                {{ feature.details }}
              </p>
            </div>
          </div>
        </div>

        <div class="mt-32">
          <h3 class="text-3xl font-bold text-center text-gray-900 mb-16">Notre engagement pour votre réussite</h3>
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
            <div v-for="stat in statistics" :key="stat.label"
                 class="text-center bg-white rounded-xl p-8 shadow-lg transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
              <p class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-400 mb-2">
                {{ stat.displayValue }}{{ stat.unit }}
              </p>
              <p class="text-xl font-medium text-gray-700">{{ stat.label }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { ChartBarIcon, ShieldCheckIcon, CubeTransparentIcon } from '@heroicons/vue/24/outline';

  const features = ref([
    {
      name: 'Gestion centralisée',
      description: 'Rassemblez toutes vos opérations immobilières sur une plateforme unique et intuitive.',
      details: 'Accédez à vos données et documents en temps réel, où que vous soyez.',
      icon: ChartBarIcon,
    },
    {
      name: 'Sécurité renforcée',
      description: 'Protégez vos données sensibles avec des mesures de sécurité de pointe.',
      details: 'Chiffrement de bout en bout, authentification multi-facteurs et conformité RGPD.',
      icon: ShieldCheckIcon,
    },
    {
      name: 'Plateforme polyvalente',
      description: 'Adaptez Noflay à tous vos besoins, quelle que soit la taille de votre portefeuille.',
      details: 'Modules personnalisables pour la gestion locative, la copropriété, et bien plus encore.',
      icon: CubeTransparentIcon,
    },
  ]);

  const statistics = ref([
    { label: 'Gain de temps estimé', value: 15, displayValue: 0, unit: 'h' },
    { label: 'Taux de disponibilité visé', value: 99.9, displayValue: 0, unit: '%' },
    { label: 'Modules intégrables', value: 20, displayValue: 0, unit: '+' },
  ]);

  const animateValue = (obj, start, end, duration) => {
    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      obj.displayValue = Number((progress * (end - start) + start).toFixed(1));
      if (progress < 1) {
        window.requestAnimationFrame(step);
      }
    };
    window.requestAnimationFrame(step);
  };

  onMounted(() => {
    statistics.value.forEach(stat => {
      animateValue(stat, 0, stat.value, 2000);
    });
  });
  </script>
