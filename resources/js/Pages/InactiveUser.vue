<template>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-lg rounded-lg sm:px-10">
                <div class="flex justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-6xl"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Accès non autorisé
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Vous n'êtes pas autorisé à accéder à l'application. Veuillez contacter l'administrateur pour plus d'informations.
                </p>
                <div class="mt-8">
                    <h3 class="text-center text-lg font-semibold text-gray-800">Contactez l'administrateur :</h3>
                    <ul class="mt-4 space-y-2 text-center">
                        <li class="text-gray-600 flex items-center justify-center">
                            <i class="fas fa-envelope text-gray-600 mr-2"></i>
                            <a href="mailto:contact@ndiagandiaye.com" class="text-gray-600 hover:underline">
                                contact@ndiagandiaye.com
                            </a>
                        </li>
                        <li class="text-gray-600 flex items-center justify-center">
                            <i class="fab fa-whatsapp text-gray-600 mr-2"></i>
                            <a href="https://wa.me/221781633419" class="text-gray-600 hover:underline">
                                +221 78 163 31 19
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'InactiveUser',
}
</script>

