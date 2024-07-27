<template>
    <AppLayout title="Modifier une entreprise">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
              <div class="text-2xl mt-8 mb-6 font-bold text-center text-gradient bg-gradient-to-r from-blue-600 to-purple-600">
                Modifier l'entreprise
              </div>

              <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div v-for="field in fields" :key="field.name" class="col-span-1">
                    <label :for="field.name" class="block text-sm font-medium text-gray-700">{{ field.label }}</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div v-if="field.icon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <component :is="field.icon" class="h-5 w-5 text-gray-400" aria-hidden="true" />
                      </div>
                      <input v-if="field.type !== 'file' && field.type !== 'select'"
                        :id="field.name"
                        v-model="form[field.name]"
                        :type="field.type"
                        :placeholder="field.placeholder"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                        :class="{ 'border-red-300': form.errors[field.name] }">
                      <input v-else-if="field.type === 'file'"
                        :id="field.name"
                        type="file"
                        @change="handleFileUpload"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        :class="{ 'border-red-300': form.errors[field.name] }">
                      <select v-else-if="field.type === 'select'"
                        :id="field.name"
                        v-model="form[field.name]"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-10 py-2 text-base border-gray-300 sm:text-sm rounded-md"
                        :class="{ 'border-red-300': form.errors[field.name] }">
                        <option value="">Sélectionnez un représentant</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                          {{ user.name }}
                        </option>
                      </select>
                    </div>
                    <p v-if="form.errors[field.name]" class="mt-2 text-sm text-red-600">{{ form.errors[field.name] }}</p>
                  </div>
                </div>

                <div class="mt-6">
                  <label class="inline-flex items-center">
                    <input type="checkbox" v-model="form.is_active" class="form-checkbox h-5 w-5 text-indigo-600">
                    <span class="ml-2 text-gray-700">Entreprise active</span>
                  </label>
                </div>

                <div class="flex items-center justify-end mt-6 space-x-3">
                  <Link :href="route('companies.index')" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    Annuler
                  </Link>
                  <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition" :disabled="form.processing">
                    Mettre à jour
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { useForm, usePage, Link } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { Building, Mail, Phone, MapPin, Globe, FileText, User } from 'lucide-vue-next';

  const props = defineProps({
    company: Object,
    users: Array,
  });

  const fields = [
    { name: 'name', label: 'Nom de l\'entreprise', type: 'text', placeholder: 'Entrez le nom de l\'entreprise', icon: Building },
    { name: 'email', label: 'Email', type: 'email', placeholder: 'email@exemple.com', icon: Mail },
    { name: 'phone', label: 'Téléphone', type: 'tel', placeholder: '+221 77 123 45 67', icon: Phone },
    { name: 'address', label: 'Adresse', type: 'text', placeholder: 'Adresse complète', icon: MapPin },
    { name: 'website', label: 'Site web', type: 'url', placeholder: 'https://www.exemple.com', icon: Globe },
    { name: 'logo', label: 'Logo', type: 'file', placeholder: 'Choisir un fichier', icon: null },
    { name: 'NINEA', label: 'NINEA', type: 'text', placeholder: 'Numéro NINEA', icon: FileText },
    { name: 'RCCM', label: 'Registre de commerce', type: 'text', placeholder: 'Numéro RCCM', icon: FileText },
    { name: 'representant_id', label: 'Représentant', type: 'select', placeholder: 'Sélectionnez un représentant', icon: User },
  ];

  const form = useForm({
    name: props.company.name,
    email: props.company.email,
    phone: props.company.phone,
    address: props.company.address,
    website: props.company.website,
    logo: null,
    NINEA: props.company.NINEA,
    RCCM: props.company.RCCM,
    representant_id: props.company.representant_id,
    is_active: props.company.is_active,
    _method: 'PUT',
  });

  const handleFileUpload = (event) => {
    form.logo = event.target.files[0];
  };

  const submit = () => {
    form.post(route('companies.update', props.company.id), {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        // Optionally, you can add a success message or redirect
      },
    });
  };
  </script>

  <style scoped>
  .text-gradient {
    @apply bg-clip-text text-transparent;
  }
  </style>
