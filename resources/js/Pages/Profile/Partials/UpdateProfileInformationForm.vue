<template>
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Informations sur le profil</h2>
        <form @submit.prevent="updateProfileInformation" class="space-y-6">
            <!-- Photo de profil -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex items-center space-x-6">
                <div class="shrink-0">
                    <img v-if="!photoPreview" :src="user.profile_photo_url" :alt="user.name"
                        class="h-16 w-16 object-cover rounded-full">
                    <span v-else class="inline-block h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                        <img :src="photoPreview" class="h-full w-full object-cover">
                    </span>
                </div>
                <div class="flex-1">
                    <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">
                    <button type="button"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300"
                        @click.prevent="selectNewPhoto">
                        Sélectionner une nouvelle photo
                    </button>
                    <button v-if="user.profile_photo_path" type="button"
                        class="ml-2 px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300"
                        @click.prevent="deletePhoto">
                        Supprimer la photo
                    </button>
                </div>
            </div>

            <!-- Nom -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input id="name" v-model="form.name" type="text"
                    class="w-full px-4 py-3 rounded-md bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-300"
                    required autocomplete="name">
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <!-- Adresse e-mail -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail</label>
                <input id="email" v-model="form.email" type="email"
                    class="w-full px-4 py-3 rounded-md bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-300"
                    required autocomplete="username">
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</p>
            </div>

            <!-- Vérification d'e-mail -->
            <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                <p class="text-sm text-gray-600 mt-2">
                    Votre adresse e-mail n'est pas vérifiée.
                    <button type="button" class="underline text-indigo-600 hover:text-indigo-800"
                        @click.prevent="sendEmailVerification">
                        Cliquez ici pour renvoyer l'e-mail de vérification.
                    </button>
                </p>
                <p v-if="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                    Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                </p>
            </div>

            <div class="flex items-center justify-end mt-6">
                <p v-if="form.recentlySuccessful" class="text-sm text-green-600 mr-3">Enregistré.</p>
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }" :disabled="form.processing">
                    {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>
