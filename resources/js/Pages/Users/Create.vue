<template>
    <AppLayout title="Ajouter un utilisateur">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-8 animate-gradient">
                        Ajouter un utilisateur
                    </h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-medium text-gray-700">Nom</label>
                                <input id="name" v-model="form.name" type="text" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                                <input id="email" v-model="form.email" type="email" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium text-gray-700">Mot de passe</label>
                                <input id="password" v-model="form.password" type="password" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                                <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                            </div>

                            <div class="space-y-2">
                                <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                <input id="password_confirmation" v-model="form.password_confirmation" type="password" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105" />
                            </div>

                            <div class="space-y-2">
                                <label for="role" class="text-sm font-medium text-gray-700">Rôle</label>
                                <select id="role" v-model="form.role" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">Sélectionnez un rôle</option>
                                    <option v-for="role in filteredRoleOptions" :key="role.value" :value="role.value">
                                        {{ role.label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</div>
                            </div>

                            <div v-if="showCompanySelect" class="space-y-2">
                                <label for="company_id" class="text-sm font-medium text-gray-700">Entreprise</label>
                                <select id="company_id" v-model="form.company_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-200 ease-in-out transform hover:scale-105">
                                    <option value="">Sélectionnez une entreprise</option>
                                    <option v-for="company in companyOptions" :key="company.value" :value="company.value">
                                        {{ company.label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.company_id" class="text-red-500 text-xs mt-1">{{ form.errors.company_id }}</div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button @click="cancel" type="button"
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Annuler
                            </button>
                            <button type="submit" :disabled="form.processing"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ form.processing ? 'Création en cours...' : 'Ajouter l\'utilisateur' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de succès -->
        <Transition name="modal">
            <div v-if="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                        <div>
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Utilisateur ajouté avec succès
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Le nouvel utilisateur a été ajouté à votre liste avec succès.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <button type="button" @click="showSuccessModal = false"
                                class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

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

const showSuccessModal = ref(false);

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
        .filter(role => currentUserRole.value === 'super_admin' || role !== 'super_admin')
        .map(role => ({
            value: role,
            label: roleMap[role] || role
        }));
});

const companyOptions = computed(() =>
    props.companies.map(company => ({
        value: company.id,
        label: company.name
    }))
);

const submit = () => {
    form.post(route('users.store'), {
        onSuccess: () => {
            form.reset();
            showSuccessModal.value = true;
        },
        onError: (errors) => {
            console.error(errors);
            alert('Une erreur est survenue lors de l\'ajout de l\'utilisateur');
        },
    });
};

const cancel = () => {
    window.history.back();
};
</script>

<style scoped>
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 5s ease infinite;
}

.modal-enter-active, .modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
    opacity: 0;
}
</style>
