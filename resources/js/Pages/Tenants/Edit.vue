<template>
    <AppLayout title="Modifier le locataire">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Modifier le locataire
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
                  <input type="text" id="first_name" v-model="form.first_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                  <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
                  <input type="text" id="last_name" v-model="form.last_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                  <input type="email" id="email" v-model="form.email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                  <label for="phone_number" class="block text-sm font-medium text-gray-700">Téléphone</label>
                  <input type="text" id="phone_number" v-model="form.phone_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                  <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                  <input type="text" id="address" v-model="form.address" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                  <label for="id_card_number" class="block text-sm font-medium text-gray-700">Numéro de carte d'identité</label>
                  <input type="text" id="id_card_number" v-model="form.id_card_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                  <label for="id_card_expiration_date" class="block text-sm font-medium text-gray-700">Date d'expiration de la carte d'identité</label>
                  <input type="date" id="id_card_expiration_date" v-model="form.id_card_expiration_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                  <label for="property_id" class="block text-sm font-medium text-gray-700">Propriété</label>
                  <select id="property_id" v-model="form.property_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option v-for="property in properties" :key="property.id" :value="property.id">
                      {{ property.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                  Mettre à jour
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script>
  import { useForm } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';

  export default {
    components: {
      AppLayout,
    },
    props: {
      tenant: Object,
      properties: Array,
    },
    setup(props) {
      const form = useForm({
        first_name: props.tenant.first_name,
        last_name: props.tenant.last_name,
        email: props.tenant.email,
        phone_number: props.tenant.phone_number,
        address: props.tenant.address,
        id_card_number: props.tenant.id_card_number,
        id_card_expiration_date: props.tenant.id_card_expiration_date,
        property_id: props.tenant.property_id,
      });

      function submit() {
        form.put(route('tenants.update', props.tenant.id));
      }

      return { form, submit };
    },
  };
  </script>
