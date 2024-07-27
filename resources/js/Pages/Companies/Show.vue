<template>
    <AppLayout title="Détails de l'entreprise">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
              <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gradient bg-gradient-to-r from-blue-600 to-purple-600">
                  {{ company.name }}
                </h2>
                <div class="flex space-x-2">
                  <Link :href="route('companies.edit', company.id)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                    Modifier
                  </Link>
                  <button @click="deleteCompany" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                    Supprimer
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="field in fields" :key="field.name" class="flex items-center">
                  <component :is="field.icon" class="h-5 w-5 text-gray-400 mr-2" />
                  <div>
                    <p class="text-sm font-medium text-gray-500">{{ field.label }}</p>
                    <p class="mt-1 text-sm text-gray-900">
                      <template v-if="field.name === 'logo'">
                        <img v-if="company.logo" :src="company.logo" alt="Logo" class="h-20 w-20 object-cover rounded-full" />
                        <span v-else>Aucun logo</span>
                      </template>
                      <template v-else-if="field.name === 'is_active'">
                        <span :class="company.is_active ? 'text-green-600' : 'text-red-600'">
                          {{ company.is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </template>
                      <template v-else>
                        {{ company[field.name] || 'Non spécifié' }}
                      </template>
                    </p>
                  </div>
                </div>
              </div>

              <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">Représentant</h3>
                <p v-if="company.representant" class="mt-1 text-sm text-gray-600">
                  {{ company.representant.name }} ({{ company.representant.email }})
                </p>
                <p v-else class="mt-1 text-sm text-gray-600">Aucun représentant spécifié</p>
              </div>

              <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">Utilisateurs associés</h3>
                <ul v-if="company.users.length" class="mt-2 border border-gray-200 rounded-md divide-y divide-gray-200">
                  <li v-for="user in company.users" :key="user.id" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                    <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">{{ user.name }} ({{ user.email }})</span>
                    </div>
                  </li>
                </ul>
                <p v-else class="mt-1 text-sm text-gray-600">Aucun utilisateur associé</p>
              </div>

              <!-- Vous pouvez ajouter des sections similaires pour les propriétaires, locataires et propriétés si nécessaire -->

            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { Link, useForm } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { Building, Mail, Phone, MapPin, Globe, FileText, User, CheckCircle } from 'lucide-vue-next';

  const props = defineProps({
    company: Object,
  });

  const fields = [
    { name: 'name', label: 'Nom de l\'entreprise', icon: Building },
    { name: 'email', label: 'Email', icon: Mail },
    { name: 'phone', label: 'Téléphone', icon: Phone },
    { name: 'address', label: 'Adresse', icon: MapPin },
    { name: 'website', label: 'Site web', icon: Globe },
    { name: 'logo', label: 'Logo', icon: FileText },
    { name: 'NINEA', label: 'NINEA', icon: FileText },
    { name: 'RCCM', label: 'Registre de commerce', icon: FileText },
    { name: 'is_active', label: 'Statut', icon: CheckCircle },
  ];

  const form = useForm({});

  const deleteCompany = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')) {
      form.delete(route('companies.destroy', props.company.id), {
        preserveState: false,
        preserveScroll: true,
      });
    }
  };
  </script>

  <style scoped>
  .text-gradient {
    @apply bg-clip-text text-transparent;
  }
  </style>
