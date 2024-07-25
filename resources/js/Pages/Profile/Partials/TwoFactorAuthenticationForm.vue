<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
const props = defineProps({
    requiresConfirmation: Boolean,
});
const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);
const confirmationForm = useForm({
    code: '',
});
const twoFactorEnabled = computed(
    () => ! enabling.value && page.props.auth.user?.two_factor_enabled,
);
watch(twoFactorEnabled, () => {
    if (! twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});
const enableTwoFactorAuthentication = () => {
    enabling.value = true;
    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};
const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};
const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}
const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};
const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};
const regenerateRecoveryCodes = () => {
    axios
        .post(route('two-factor.recovery-codes'))
        .then(() => showRecoveryCodes());
};
const disableTwoFactorAuthentication = () => {
    disabling.value = true;
    router.delete(route('two-factor.disable'), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Authentification à deux facteurs</h2>

        <p class="text-sm text-gray-600 mb-4">
            Ajoutez une sécurité supplémentaire à votre compte grâce à l'authentification à deux facteurs.
        </p>

        <h3 v-if="twoFactorEnabled && !confirming" class="text-lg font-medium text-gray-900 mb-4">
            Vous avez activé l'authentification à deux facteurs.
        </h3>

        <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium text-gray-900 mb-4">
            Terminez l'activation de l'authentification à deux facteurs.
        </h3>

        <h3 v-else class="text-lg font-medium text-gray-900 mb-4">
            Vous n'avez pas activé l'authentification à deux facteurs.
        </h3>

        <div v-if="twoFactorEnabled">
            <div v-if="qrCode" class="mb-4">
                <p class="text-sm text-gray-600 mb-2">
                    {{ confirming ? 'Pour terminer l\'activation de l\'authentification à deux facteurs, scannez le code QR suivant à l\'aide de l\'application d\'authentification de votre téléphone ou entrez la clé de configuration et fournissez le code OTP généré.' : 'L\'authentification à deux facteurs est désormais activée. Scannez le code QR suivant à l\'aide de l\'application d\'authentification de votre téléphone ou saisissez la clé de configuration.' }}
                </p>
                <div class="p-2 inline-block bg-white" v-html="qrCode"></div>

                <div v-if="setupKey" class="mt-2">
                    <p class="text-sm font-semibold text-gray-700">Clé de configuration: <span v-text="setupKey"></span></p>
                </div>
            </div>

            <div v-if="confirming" class="mb-4">
                <TextInput
                    v-model="confirmationForm.code"
                    type="text"
                    placeholder="Code"
                    class="w-full px-4 py-3 rounded-md bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-300"
                />
                <InputError :message="confirmationForm.errors.code" class="mt-1" />
            </div>

            <div v-if="recoveryCodes.length > 0 && !confirming" class="mb-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Codes de récupération:</p>
                <div class="bg-gray-100 p-2 rounded-md">
                    <p v-for="code in recoveryCodes" :key="code" class="text-xs font-mono">{{ code }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                <button
                    v-if="!twoFactorEnabled"
                    class="bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    :class="{ 'opacity-50 cursor-not-allowed': enabling }"
                    :disabled="enabling"
                >
                    {{ enabling ? 'Activation...' : 'Activer' }}
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                <button
                    v-if="confirming"
                    class="bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Confirmer
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                <button
                    v-if="recoveryCodes.length > 0 && !confirming"
                    class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-md hover:bg-gray-300 transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                >
                    Régénérer les codes
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="showRecoveryCodes">
                <button
                    v-if="recoveryCodes.length === 0 && !confirming"
                    class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-md hover:bg-gray-300 transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                >
                    Afficher les codes
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                <button
                    v-if="twoFactorEnabled && !confirming"
                    class="bg-red-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-red-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    :class="{ 'opacity-50 cursor-not-allowed': disabling }"
                    :disabled="disabling"
                >
                    {{ disabling ? 'Désactivation...' : 'Désactiver' }}
                </button>
            </ConfirmsPassword>
        </div>
    </div>
</template>
