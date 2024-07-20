<template>
    <AppLayout title="Modifier un utilisateur">
        <div class="py-12 bg-gray-100">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Modifier un utilisateur</h1>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" id="name" v-model="form.name"
                                               class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                               required>
                                    </div>
                                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name
                                        }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" id="email" v-model="form.email"
                                               class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                               required>
                                    </div>
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email
                                        }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                                    <select id="role" v-model="form.role"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="" disabled>Sélectionnez un rôle</option>
                                        <option v-for="role in roles" :key="role" :value="role">{{ formatRole(role) }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.role" class="text-red-500 text-sm mt-1">{{ form.errors.role
                                        }}</div>
                                </div>

                                <div v-if="showCompanySelect" class="sm:col-span-2">
                                    <label for="company_id"
                                           class="block text-sm font-medium text-gray-700">Entreprise</label>
                                    <select id="company_id" v-model="form.company_id"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="" disabled>Sélectionnez une entreprise</option>
                                        <option v-for="company in companies" :key="company.id" :value="company.id">{{
                                                company.name }}</option>
                                    </select>
                                    <div v-if="form.errors.company_id" class="text-red-500 text-sm mt-1">{{
                                            form.errors.company_id }}</div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="button" @click="cancel"
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        :disabled="form.processing">
                                    {{ form.processing ? 'Mise à jour en cours...' : 'Mettre à jour l\'utilisateur' }}
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
import { ref, computed, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    user: Object,
    roles: {
        type: Array,
        required: true
    },
    companies: {
        type: Array,
        required: true
    }
});

const form = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    password: '',
    password_confirmation: '',
    role: props.user.role || '',
    company_id: props.user.company_id || null,
});

const showCompanySelect = computed(() => {
    return ['admin_entreprise', 'user_entreprise', 'bailleur', 'locataire'].includes(form.role);
});

const formatRole = (role) => {
    const roleMap = {
        'super_admin': 'Super Administrateur',
        'admin_entreprise': 'Administrateur d\'entreprise',
        'user_entreprise': 'Utilisateur d\'entreprise',
        'bailleur': 'Bailleur',
        'locataire': 'Locataire'
    };
    return roleMap[role] || role;
};

const submit = () => {
    form.put(route('users.update', props.user.id), {
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};

const cancel = () => {
    window.history.back()
};

onMounted(() => {
    if (props.user && props.user.role) {
        form.role = props.user.role.name;
    }
});
</script>
