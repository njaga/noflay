<template>
    <AppLayout title="Créer une entreprise">
        <div class="py-12 bg-gray-100">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Créer une nouvelle entreprise</h1>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nom de l'entreprise</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-building"></i>
                                        </span>
                                        <input type="text" id="name" v-model="form.name"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                            required>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" id="email" v-model="form.email"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    </div>
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel" id="phone" v-model="form.phone"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <input type="text" id="address" v-model="form.address"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="website" class="block text-sm font-medium text-gray-700">Site web</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-globe"></i>
                                        </span>
                                        <input type="text" id="website" v-model="form.website"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                            placeholder="www.example.com">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="is_active" v-model="form.is_active"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_active" class="font-medium text-gray-700">Entreprise active</label>
                                            <p class="text-gray-500">Cochez cette case si l'entreprise est actuellement active.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="button" @click="cancel"
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Créer l'entreprise
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

export default defineComponent({
    components: {
        AppLayout,
    },
    setup() {
        const form = useForm({
            name: '',
            email: '',
            phone: '',
            address: '',
            website: '',
            is_active: true,
        })

        const submit = () => {
            form.post(route('companies.store'), {
                onSuccess: () => {
                    form.reset()
                },
            })
        }

        const cancel = () => {
            form.reset()
        }

        return { form, submit, cancel }
    }
})
</script>

<style scoped>
/* Ajuster la largeur du conteneur principal et centrer */
.max-w-4xl {
    max-width: 40rem; /* Adjust this value to your needs */
}

/* Les boutons côte à côte */
.flex.justify-end {
    justify-content: flex-start;
}

button[type="submit"] {
    margin-left: auto;
}
</style>
