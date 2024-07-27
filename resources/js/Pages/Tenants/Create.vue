<template>
    <AppLayout title="Ajouter un locataire">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Ajouter un locataire</h2>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
                            <input type="text" id="first_name" v-model="form.first_name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            <div v-if="form.errors.first_name" class="text-red-500 text-xs mt-1">{{ form.errors.first_name }}</div>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" id="last_name" v-model="form.last_name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            <div v-if="form.errors.last_name" class="text-red-500 text-xs mt-1">{{ form.errors.last_name }}</div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" v-model="form.email"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" id="phone_number" v-model="form.phone_number"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            <div v-if="form.errors.phone_number" class="text-red-500 text-xs mt-1">{{ form.errors.phone_number }}</div>
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input type="text" id="address" v-model="form.address"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
                        </div>
                        <div>
                            <label for="id_card_number" class="block text-sm font-medium text-gray-700">Numéro CNI/Passport</label>
                            <input type="text" id="id_card_number" v-model="form.id_card_number"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            <div v-if="form.errors.id_card_number" class="text-red-500 text-xs mt-1">{{ form.errors.id_card_number }}</div>
                        </div>
                        <div>
                            <label for="id_card_expiration_date" class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                            <input type="date" id="id_card_expiration_date" v-model="form.id_card_expiration_date"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required />
                            <div v-if="form.errors.id_card_expiration_date" class="text-red-500 text-xs mt-1">{{ form.errors.id_card_expiration_date }}</div>
                        </div>
                        <div v-if="isSuperAdmin">
                            <label for="company_id" class="block text-sm font-medium text-gray-700">Entreprise</label>
                            <select id="company_id" v-model="form.company_id"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="" disabled>Sélectionnez une entreprise</option>
                                <option v-for="company in companies" :key="company.id" :value="company.id">
                                    {{ company.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.company_id" class="text-red-500 text-xs mt-1">{{ form.errors.company_id }}</div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
                            :disabled="form.processing">
                            {{ form.processing ? 'Envoi en cours...' : 'Ajouter' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = usePage().props;
const companies = ref(props.companies || []);
const isSuperAdmin = computed(() => props.auth.user.roles.includes('super_admin'));

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    address: '',
    id_card_number: '',
    id_card_expiration_date: '',
    company_id: isSuperAdmin.value ? '' : props.auth.user.company_id,
});

const submit = () => {
    form.post(route('tenants.store'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            // La redirection sera gérée par le contrôleur
        },
        onError: () => {
            // Les erreurs seront automatiquement affichées grâce à v-if="form.errors.field_name"
        },
    });
};
</script>
