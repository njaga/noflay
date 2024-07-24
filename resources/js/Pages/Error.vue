<template>
    <div class="min-h-screen bg-gradient-to-b from-gray-100 to-gray-200 flex flex-col justify-center items-center p-4">
      <div class="max-w-md w-full bg-white shadow-xl rounded-2xl p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-indigo-500"></div>
        <div class="mb-8">
          <component :is="icon" class="w-32 h-32 mx-auto text-indigo-500 animate-bounce" />
        </div>
        <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ title }}</h1>
        <p class="text-xl text-gray-600 mb-8">{{ description }}</p>
        <button @click="handleAction" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
          {{ actionText }}
        </button>
      </div>
    </div>
  </template>

  <script setup>
  import { computed, onMounted } from 'vue'
  import { router } from '@inertiajs/vue3'
  import { AlertTriangle, Server, FileQuestion, Lock, RefreshCw, LogOut } from 'lucide-vue-next'

  const props = defineProps({
  status: Number,
  message: String,
  expirationTime: Number
})

  const title = computed(() => {
    return {
      503: '503: Service Indisponible',
      500: '500: Erreur Serveur',
      404: '404: Page Non Trouvée',
      403: '403: Accès Interdit',
      401: '401: Non Autorisé',
      419: '419: Page Expirée',
    }[props.status] || 'Erreur'
  })

  const description = computed(() => {
    if (props.message) return props.message
    return {
      503: 'Désolé, nous effectuons une maintenance. Veuillez réessayer plus tard.',
      500: 'Oups, quelque chose s\'est mal passé sur nos serveurs.',
      404: 'Désolé, la page que vous recherchez est introuvable.',
      403: 'Désolé, vous n\'êtes pas autorisé à accéder à cette page.',
      401: 'Votre session a expiré. Veuillez vous reconnecter.',
      419: 'Votre session a expiré. Veuillez rafraîchir la page et réessayer.',
    }[props.status] || 'Une erreur inattendue s\'est produite.'
  })

  const icon = computed(() => {
    return {
      503: Server,
      500: AlertTriangle,
      404: FileQuestion,
      403: Lock,
      401: LogOut,
      419: RefreshCw,
    }[props.status] || AlertTriangle
  })

  const actionText = computed(() => {
  return props.status === 401 ? 'Se reconnecter maintenant' :
         props.status === 419 ? 'Rafraîchir' : 'Retour'
})

const formattedRemainingTime = computed(() => {
  const minutes = Math.floor(remainingTime.value / 60)
  const seconds = remainingTime.value % 60
  return `${minutes} minute${minutes !== 1 ? 's' : ''} et ${seconds} seconde${seconds !== 1 ? 's' : ''}`
})

const handleAction = () => {
  if (props.status === 401) {
    router.visit('/login')
  } else if (props.status === 419) {
    window.location.reload()
  } else {
    window.history.back()
  }
}

onMounted(() => {
  if (props.status === 401 && props.expirationTime) {
    const updateRemainingTime = () => {
      const now = Math.floor(Date.now() / 1000)
      remainingTime.value = Math.max(0, props.expirationTime - now)

      if (remainingTime.value <= 0) {
        clearInterval(timer)
        router.visit('/login')
      }
    }

    updateRemainingTime()
    const timer = setInterval(updateRemainingTime, 1000)
  }
})

  </script>
