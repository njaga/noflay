<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

const activeTab = ref('profile');
</script>

<template>
    <AppLayout title="Profil">
        <div class="py-12 pt-0">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div>
                    <div class="p-6 sm:p-8">
                        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Mon Profil</h2>

                        <div class="mb-8">
                            <nav class="flex space-x-4 justify-center">
                                <button @click="activeTab = 'profile'" :class="['px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200', activeTab === 'profile' ? 'bg-indigo-500 text-white' : 'text-gray-600 hover:bg-blue-100']">
                                    Profil
                                </button>
                                <button @click="activeTab = 'security'" :class="['px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200', activeTab === 'security' ? 'bg-indigo-500 text-white' : 'text-gray-600 hover:bg-blue-100']">
                                    Sécurité
                                </button>
                                <button @click="activeTab = 'sessions'" :class="['px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200', activeTab === 'sessions' ? 'bg-indigo-500 text-white' : 'text-gray-600 hover:bg-blue-100']">
                                    Sessions
                                </button>
                            </nav>
                        </div>

                        <div v-if="activeTab === 'profile'" class="space-y-8">
                            <UpdateProfileInformationForm :user="$page.props.auth.user" />
                        </div>

                        <div v-if="activeTab === 'security'" class="space-y-8">
                            <UpdatePasswordForm />
                            <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication" />
                            <DeleteUserForm />
                        </div>

                        <div v-if="activeTab === 'sessions'">
                            <LogoutOtherBrowserSessionsForm :sessions="sessions" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
