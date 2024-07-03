<template>
    <AppLayout title="Ajouter un utilisateur">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden transform hover:scale-105 transition-all duration-300">
                    <div class="md:flex">
                        <div class="md:flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 md:w-48 flex flex-col justify-center items-center py-8 px-4">
                            <div class="text-white text-4xl mb-4">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h2 class="text-white text-2xl font-semibold text-center">Ajouter un utilisateur</h2>
                        </div>
                        <div class="p-8 flex-grow">
                            <form @submit.prevent="submit" class="space-y-6">
                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                    <FloatingInput
                                        v-model="form.name"
                                        label="Nom"
                                        icon="fas fa-user"
                                        :error="form.errors.name"
                                    />
                                    <FloatingInput
                                        v-model="form.email"
                                        label="Email"
                                        type="email"
                                        icon="fas fa-envelope"
                                        :error="form.errors.email"
                                    />
                                    <FloatingInput
                                        v-model="form.password"
                                        label="Mot de passe"
                                        type="password"
                                        icon="fas fa-lock"
                                        :error="form.errors.password"
                                    />
                                    <FloatingInput
                                        v-model="form.password_confirmation"
                                        label="Confirmer le mot de passe"
                                        type="password"
                                        icon="fas fa-lock"
                                    />
                                    <AnimatedSelect
                                        v-model="form.role"
                                        :options="filteredRoleOptions"
                                        label="Rôle"
                                        :error="form.errors.role"
                                    />
                                    <AnimatedSelect
                                        v-if="showCompanySelect"
                                        v-model="form.company_id"
                                        :options="companyOptions"
                                        label="Entreprise"
                                        :error="form.errors.company_id"
                                    />
                                </div>
                                <div class="flex justify-end mt-6 space-x-4">
                                    <button @click="cancel" type="button" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-300">
                                        Annuler
                                    </button>
                                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:scale-105" :disabled="form.processing">
                                        {{ form.processing ? 'Création en cours...' : 'Ajouter l\'utilisateur' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FloatingInput from '@/Components/FloatingInput.vue';
import AnimatedSelect from '@/Components/AnimatedSelect.vue';

const props = defineProps({
    roles: Array,
    companies: Array,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
    company_id: null,
});

const showCompanySelect = computed(() =>
    ['admin_entreprise', 'user_entreprise', 'bailleur', 'locataire'].includes(form.role)
);

const currentUserRole = computed(() => usePage().props.auth.user.role);

const filteredRoleOptions = computed(() => {
    const roleMap = {
        'super_admin': 'Super Administrateur',
        'admin_entreprise': 'Administrateur d\'entreprise',
        'user_entreprise': 'Utilisateur d\'entreprise',
        'bailleur': 'Bailleur',
        'locataire': 'Locataire'
    };

    return props.roles
        .filter(role => currentUserRole.value !== 'admin_entreprise' || role !== 'super_admin')
        .map(role => ({
            value: role,
            label: roleMap[role] || role
        }));
});

const companyOptions = computed(() => [
    { value: '', label: 'Sélectionnez une entreprise' },
    ...props.companies.map(company => ({
        value: company.id,
        label: company.name
    }))
]);

const submit = () => {
    form.post(route('users.store'), {
        onSuccess: () => form.reset(),
    });
};

const cancel = () => {
    window.history.back();
};
</script>
