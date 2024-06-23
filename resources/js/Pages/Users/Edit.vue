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
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" id="name" v-model="form.name"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                            required>
                                    </div>
                                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" id="email" v-model="form.email"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                            required>
                                    </div>
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe (optionnel)</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" id="password" v-model="form.password"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    </div>
                                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" id="password_confirmation" v-model="form.password_confirmation"
                                            class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                                    <select id="role" v-model="form.role"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="" disabled>Sélectionnez un rôle</option>
                                        <option v-for="role in roles" :key="role" :value="role">{{ formatRole(role) }}</option>
                                    </select>
                                    <div v-if="form.errors.role" class="text-red-500 text-sm mt-1">{{ form.errors.role }}</div>
                                </div>

                                <div v-if="showCompanySelect" class="sm:col-span-2">
                                    <label for="company_id" class="block text-sm font-medium text-gray-700">Entreprise</label>
                                    <select id="company_id" v-model="form.company_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="" disabled>Sélectionnez une entreprise</option>
                                        <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
                                    </select>
                                    <div v-if="form.errors.company_id" class="text-red-500 text-sm mt-1">{{ form.errors.company_id }}</div>
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
                                    {{ form.processing ? 'Modification en cours...' : 'Modifier l\'utilisateur' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showSuccessModal" @close="closeSuccessModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Utilisateur modifié avec succès
                </h2>
                <p class="text-sm text-gray-600 mb-4">
                    Les informations de l'utilisateur ont été mises à jour avec succès.
                </p>
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="closeSuccessModal"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Fermer
                    </button>
                </div>
            </div>
        </Modal>

        <Modal :show="showErrorModal" @close="closeErrorModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-red-600 mb-4">
                    Erreur lors de la modification
                </h2>
                <p class="text-sm text-gray-600 mb-4">
                    Une erreur est survenue lors de la modification de l'utilisateur. Veuillez réessayer.
                </p>
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="closeErrorModal"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Fermer
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
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
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.role,
    company_id: props.user.company_id,
});

const showCompanySelect = computed(() => {
    return ['admin_entreprise', 'user_entreprise', 'bailleur', 'locataire'].includes(form.role);
});

const showSuccessModal = ref(false);
const showErrorModal = ref(false);

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
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            showSuccessModal.value = true;
        },
        onError: () => {
            showErrorModal.value = true;
        },
    });
};

const cancel = () => {
    window.history.back();
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
};

const closeErrorModal = () => {
    showErrorModal.value = false;
};
</script>
