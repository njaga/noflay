<template>
    <Head>
        <title>Session expirée - {{ appName }}</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        />
    </Head>
    <div
        class="min-vh-100 d-flex align-items-center justify-content-center bg-light"
    >
        <div class="card shadow-lg" style="max-width: 400px">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <i
                        class="fas fa-frown fa-5x text-warning animated-icon"
                    ></i>
                </div>
                <h2 class="card-title mb-4 fw-bold">Session expirée</h2>
                <p class="card-text text-muted mb-4">
                    Votre session a expiré en raison d'inactivité. Veuillez vous
                    reconnecter pour continuer.
                </p>
                <button
                    @click="redirectToLogin"
                    class="btn btn-indigo btn-lg w-100"
                >
                    Se reconnecter
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        Head,
    },
    data() {
        return {
            appName: "Votre Application", // Remplacez par le nom de votre application
        };
    },
    mounted() {
        // Empêcher la redirection automatique
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = this.handlePopState;
    },
    beforeUnmount() {
        // Nettoyer l'événement avant de démonter le composant
        window.onpopstate = null;
    },
    methods: {
        redirectToLogin() {
            window.location.href = "/login";
        },
        handlePopState(event) {
            // Empêcher la navigation arrière
            window.history.pushState(null, "", window.location.href);
        },
    },
};
</script>

<style scoped>
.animated-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.btn-indigo {
    background-color: #4f46e5;
    border-color: #4f46e5;
    color: white;
}

.btn-indigo:hover,
.btn-indigo:focus {
    background-color: #4338ca;
    border-color: #4338ca;
    color: white;
}
</style>
