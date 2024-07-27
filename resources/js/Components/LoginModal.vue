<template>
    <div
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4"
    >
        <div
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 ease-out scale-95 hover:scale-100"
        >
            <div class="p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2
                        class="text-3xl font-extrabold text-indigo-800 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600"
                    >
                        Connexion Noflay
                    </h2>
                    <button
                        @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200 transform hover:scale-110"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitLogin" class="space-y-6">
                    <div class="relative group">
                        <input
                            type="email"
                            id="login-email"
                            v-model="credentials.email"
                            class="peer w-full border-0 border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 bg-transparent transition-all duration-300 pt-6 pb-2"
                            placeholder="Email"
                            required
                        />
                        <label
                            for="login-email"
                            class="absolute left-0 top-2 text-gray-600 text-sm transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:text-indigo-600 peer-focus:text-sm"
                        >
                            Email
                        </label>
                    </div>
                    <div class="relative group">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="login-password"
                            v-model="credentials.password"
                            class="peer w-full border-0 border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 bg-transparent pr-10 transition-all duration-300 pt-6 pb-2"
                            placeholder="Mot de passe"
                            required
                        />
                        <label
                            for="login-password"
                            class="absolute left-0 top-2 text-gray-600 text-sm transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:text-indigo-600 peer-focus:text-sm"
                        >
                            Mot de passe
                        </label>
                        <button
                            type="button"
                            @click="togglePasswordVisibility"
                            class="absolute inset-y-0 right-0 flex items-center text-gray-400 hover:text-indigo-600 transition-colors duration-200"
                        >
                            <svg
                                v-if="!showPassword"
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                ></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                ></path>
                            </svg>
                            <svg
                                v-else
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                ></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <label
                            class="flex items-center text-gray-600 hover:text-indigo-600 cursor-pointer transition-colors duration-200"
                        >
                            <input
                                type="checkbox"
                                v-model="rememberMe"
                                class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out rounded"
                            />
                            <span class="ml-2">Se souvenir de moi</span>
                        </label>
                        <a
                            :href="route('password.request')"
                            class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200"
                            >Mot de passe oublié ?</a
                        >
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full py-3 px-4 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105 font-semibold text-lg flex items-center justify-center"
                        :disabled="isLoading"
                    >
                        <span v-if="!isLoading">Se connecter</span>
                        <span v-else class="flex items-center">
                            <svg
                                class="animate-spin h-5 w-5 mr-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"
                                ></path>
                            </svg>
                            Connexion en cours...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";

const emit = defineEmits(["close", "login"]);
const credentials = reactive({
    email: "",
    password: "",
});
const showPassword = ref(false);
const rememberMe = ref(false);
const isLoading = ref(false);

const submitLogin = () => {
    isLoading.value = true;
    emit("login", { ...credentials, remember: rememberMe.value });
    // Simuler une requête réseau avec un délai
    setTimeout(() => {
        isLoading.value = false;
    }, 2000);
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<style scoped>
.form-checkbox {
    appearance: none;
    padding: 0;
    print-color-adjust: exact;
    display: inline-block;
    vertical-align: middle;
    background-origin: border-box;
    user-select: none;
    flex-shrink: 0;
    height: 1rem;
    width: 1rem;
    color: #4f46e5;
    background-color: #fff;
    border-color: #6b7280;
    border-width: 1px;
    border-radius: 0.25rem;
}

.form-checkbox:checked {
    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
    border-color: transparent;
    background-color: currentColor;
    background-size: 100% 100%;
    background-position: center;
    background-repeat: no-repeat;
}
</style>
