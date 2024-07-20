<template>
    <AppLayout title="Modifier une propriété">
      <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
          <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-900 mb-8">
            <i class="fas fa-edit mr-2 text-indigo-600"></i>Modifier une propriété
          </h1>
          <form @submit.prevent="submit">
            <div class="mb-4">
              <label for="landlord_id" class="block text-sm font-medium text-gray-700">Bailleur</label>
              <select id="landlord_id" v-model="form.landlord_id" class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">{{ landlord.first_name }} {{ landlord.last_name }}</option>
              </select>
            </div>
            <div class="mb-4">
              <label for="property_type" class="block text-sm font-medium text-gray-700">Type de propriété</label>
              <input id="property_type" v-model="form.property_type" type="text" required class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700">Nom de la propriété</label>
              <input id="name" v-model="form.name" type="text" required class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            <div class="mb-4">
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <textarea id="description" v-model="form.description" class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="mb-4">
              <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
              <input id="address" v-model="form.address" type="text" required class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            <div class="mb-4">
              <label for="available_count" class="block text-sm font-medium text-gray-700">Nombre libre</label>
              <input id="available_count" v-model="form.available_count" type="number" required class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            <div class="mb-4">
              <label for="photos" class="block text-sm font-medium text-gray-700">Photos (jusqu'à 5)</label>
              <input id="photos" type="file" accept="image/jpeg, image/png, image/gif, image/webp" multiple @change="handleFileUpload" class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <img
                  v-for="(photo, index) in form.photos"
                  :key="index"
                  :src="photoUrl(photo)"
                  alt="Property Image"
                  class="w-full h-32 object-cover rounded-md"
                />
              </div>
            </div>
            <div class="mb-4" v-if="auth.user.roles.includes('super_admin')">
              <label for="company_id" class="block text-sm font-medium text-gray-700">Entreprise</label>
              <select id="company_id" v-model="form.company_id" class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
              </select>
            </div>
            <div class="flex justify-end">
              <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Enregistrer
              </button>
            </div>
          </form>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';

  const { props } = usePage();
  const auth = props.auth;
  const property = props.property;
  const companies = props.companies;
  const landlords = props.landlords;

  const form = useForm({
    landlord_id: property.landlord_id,
    property_type: property.property_type,
    name: property.name,
    description: property.description,
    address: property.address,
    available_count: property.available_count,
    company_id: property.company_id,
    photos: property.photos ? property.photos.map(photo => ({ name: photo, url: `/storage/${photo}` })) : [],
  });

  const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    form.photos = files.map(file => ({ file, url: URL.createObjectURL(file) }));
  };

  const photoUrl = (photo) => {
    return photo.file ? photo.url : photo.url;
  };

  const submit = () => {
    const data = new FormData();
    data.append('landlord_id', form.landlord_id);
    data.append('property_type', form.property_type);
    data.append('name', form.name);
    data.append('description', form.description);
    data.append('address', form.address);
    data.append('available_count', form.available_count);
    data.append('company_id', form.company_id);

    form.photos.forEach((photo, index) => {
      if (photo.file) {
        data.append(`photos[${index}]`, photo.file);
      } else {
        data.append(`photos[${index}]`, photo.name);
      }
    });

    form.put(route('properties.update', property.id), {
      preserveState: true,
      preserveScroll: true,
      data,
    });
  };
  </script>
