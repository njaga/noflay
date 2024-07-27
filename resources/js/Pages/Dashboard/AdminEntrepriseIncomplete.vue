<template>
    <AppLayout :title="`Profil Incomplet - ${role}`">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold mb-4 text-gray-800">
                            Profil Incomplet
                        </h2>
                        <p class="mb-4 text-gray-600">{{ message }}</p>
                        <div class="mt-6">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                @click="completeProfile">
                                Compléter mon profil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    message: String,
    role: String,
    auth: Object,
});

const form = useForm({});

const completeProfile = () => {
    // Redirect to the appropriate profile completion page based on the role
    switch (props.role) {
        case 'admin_entreprise':
            form.get(route('admin.profile.edit'));
            break;
        case 'bailleur':
            form.get(route('landlord.profile.edit'));
            break;
        case 'locataire':
            form.get(route('tenant.profile.edit'));
            break;
        default:
            form.get(route('profile.edit'));
    }
};
</script>
