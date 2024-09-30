<template>
    <AppLayout title="Compléter votre profil de bailleur">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <h2 class="text-2xl font-semibold mb-6">Compléter votre profil de bailleur</h2>
              <form @submit.prevent="submitForm">
                <div class="mb-4">
                  <label for="company_name" class="block text-sm font-medium text-gray-700">Nom de l'entreprise</label>
                  <input type="text" id="company_name" v-model="form.company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                  <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                  <input type="text" id="address" v-model="form.address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                  <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                  <input type="tel" id="phone" v-model="form.phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                  <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                  <input type="email" id="email" v-model="form.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                  <label for="tax_id" class="block text-sm font-medium text-gray-700">Numéro d'identification fiscale</label>
                  <input type="text" id="tax_id" v-model="form.tax_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                  <label for="bank_info" class="block text-sm font-medium text-gray-700">Informations bancaires</label>
                  <textarea id="bank_info" v-model="form.bank_info" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                </div>
                <div class="flex items-center justify-end mt-4">
                  <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    Enregistrer le profil
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
  import { ref } from 'vue'
  import { useForm } from '@inertiajs/vue3'
  import AppLayout from '@/Layouts/AppLayout.vue'

  const form = useForm({
    company_name: '',
    address: '',
    phone: '',
    email: '',
    tax_id: '',
    bank_info: ''
  })

  const submitForm = () => {
    form.post(route('landlord.profile.complete'), {
      preserveScroll: true,
      onSuccess: () => {
        // Rediriger vers le tableau de bord du bailleur ou afficher un message de succès
      },
    })
  }
  </script>
