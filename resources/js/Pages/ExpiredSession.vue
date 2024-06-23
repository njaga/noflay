<script setup>
import { router } from '@inertiajs/vue3';

const logoutAndRedirect = () => {
    router.post(route('logout'), {}, {
        preserveState: false,
        preserveScroll: false,
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-lg rounded-lg sm:px-10">
                <div class="flex justify-center">
                    <i class="fas fa-hourglass-end text-red-500 text-6xl"></i>
                </div>
                <h1 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Session expirée
                </h1>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Votre session a expirée. Veuillez vous reconnecter.
                </p>
                <div class="mt-6 text-center">
                    <button @click="logoutAndRedirect" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75">
                        Se reconnecter
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
