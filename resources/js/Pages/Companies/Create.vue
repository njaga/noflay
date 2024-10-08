<template>
    <AppLayout title="Créer une entreprise">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-center text-gradient bg-gradient-to-r from-blue-600 to-purple-600 mb-6">
                            Créer une nouvelle entreprise
                        </h1>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div v-for="field in fields" :key="field.name" class="relative">
                                <label :for="field.name" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ field.label }}
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div v-if="field.icon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <component :is="field.icon" class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <input v-if="field.type !== 'file' && field.type !== 'select'"
                                        :id="field.name"
                                        v-model="form[field.name]"
                                        :type="field.type"
                                        :placeholder="field.placeholder"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md transition duration-150 ease-in-out"
                                        :class="{ 'border-red-300': form.errors[field.name] }">
                                    <input v-else-if="field.type === 'file'"
                                        :id="field.name"
                                        type="file"
                                        @change="handleFileUpload"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md transition duration-150 ease-in-out"
                                        :class="{ 'border-red-300': form.errors[field.name] }">
                                    <select v-else-if="field.type === 'select'"
                                        :id="field.name"
                                        v-model="form[field.name]"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-10 py-2 text-base border-gray-300 sm:text-sm rounded-md transition duration-150 ease-in-out"
                                        :class="{ 'border-red-300': form.errors[field.name] }">
                                        <option value="">Sélectionnez un représentant</option>
                                        <option v-for="user in users" :key="user.id" :value="user.id">
                                            {{ user.name }}
                                        </option>
                                    </select>
                                </div>
                                <p v-if="form.errors[field.name]" class="mt-2 text-sm text-red-600">
                                    {{ form.errors[field.name] }}
                                </p>
                            </div>

                            <div class="flex items-center">
                                <input id="is_active" v-model="form.is_active" type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition duration-150 ease-in-out">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Entreprise active
                                </label>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" @click="resetForm"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                    Annuler
                                </button>
                                <button type="submit" :disabled="form.processing"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                    <span v-if="form.processing">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Création en cours...
                                    </span>
                                    <span v-else>Créer l'entreprise</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    Entreprise créée avec succès
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        L'entreprise "{{ createdCompanyName }}" a été ajoutée à la base de données.
                    </p>
                </div>

                <div class="mt-4">
                    <button type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="closeModal">
                        Fermer
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Modal from '@/Components/Modal.vue'
import { Building, Mail, Phone, MapPin, Globe, FileText, User } from 'lucide-vue-next'

const showModal = ref(false)
const createdCompanyName = ref('')

const fields = [
    { name: 'name', label: 'Nom de l\'entreprise', type: 'text', placeholder: 'Entrez le nom de l\'entreprise', icon: Building },
    { name: 'email', label: 'Email', type: 'email', placeholder: 'email@exemple.com', icon: Mail },
    { name: 'phone', label: 'Téléphone', type: 'tel', placeholder: '+221 77 123 45 67', icon: Phone },
    { name: 'address', label: 'Adresse', type: 'text', placeholder: 'Adresse complète', icon: MapPin },
    { name: 'website', label: 'Site web', type: 'url', placeholder: 'https://www.exemple.com', icon: Globe },
    { name: 'logo', label: 'Logo', type: 'file', placeholder: 'Choisir un fichier', icon: Image },
    { name: 'NINEA', label: 'NINEA', type: 'text', placeholder: 'Numéro NINEA', icon: FileText },
    { name: 'RCCM', label: 'Registre de commerce', type: 'text', placeholder: 'Numéro RCCM', icon: FileText },
    { name: 'representant_id', label: 'Représentant', type: 'select', placeholder: 'Sélectionnez un représentant', icon: User },
]

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    website: '',
    logo: null,
    NINEA: '',
    RCCM: '',
    representant_id: '',
    is_active: true,
})

const users = ref([])

onMounted(async () => {
    const response = await fetch('/api/users')
    users.value = await response.json()
})

const handleFileUpload = (event) => {
    const file = event.target.files[0]
    if (file && (file.type === 'image/jpeg' || file.type === 'image/png') && file.size <= 2 * 1024 * 1024) {
        form.logo = file
    } else {
        alert('Veuillez sélectionner une image JPG ou PNG de moins de 2 MB.')
        event.target.value = '' // Reset the file input
    }
}

const submit = () => {
    form.post(route('companies.store'), {
        preserveState: true,
        preserveScroll: true,
    })
}

const resetForm = () => {
    form.reset()
    form.clearErrors()
}

const closeModal = () => {
    showModal.value = false
}

const flash = computed(() => usePage().props.flash)

watch(flash, (newFlash) => {
    if (newFlash && newFlash.success) {
        createdCompanyName.value = newFlash.company ? newFlash.company.name : ''
        showModal.value = true
        form.reset()
    }
}, { immediate: true })

onMounted(() => {
    if (flash.value && flash.value.success) {
        createdCompanyName.value = flash.value.company ? flash.value.company.name : ''
        showModal.value = true
    }
})
</script>

<style scoped>
.text-gradient {
    @apply bg-clip-text text-transparent;
}
</style>
