<template>
    <div class="fixed bottom-4 right-4 z-50">
        <transition name="bounce">
            <div v-if="!isOpen" @click="toggleChat" class="cursor-pointer">
                <div class="relative">
                    <div
                        class="bg-indigo-600 text-white p-3 rounded-full shadow-lg"
                    >
                        <i class="fas fa-comment-dots text-2xl"></i>
                    </div>
                    <transition name="fade">
                        <div
                            v-if="unreadMessages > 0"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-6 h-6 flex items-center justify-center animate-pulse"
                        >
                            {{ unreadMessages }}
                        </div>
                    </transition>
                </div>
            </div>
        </transition>

        <transition name="slide-fade">
            <div
                v-if="isOpen"
                class="w-80 bg-white rounded-lg shadow-2xl overflow-hidden"
            >
                <div
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-4 flex justify-between items-center"
                >
                    <span class="font-bold text-lg">Chat</span>
                    <button
                        @click="toggleChat"
                        class="text-white hover:text-gray-200 focus:outline-none"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="h-64 overflow-y-auto p-4 bg-gray-100">
                    <div
                        v-for="(message, index) in messages"
                        :key="index"
                        class="mb-4"
                    >
                        <div
                            :class="[
                                'max-w-3/4 p-3 rounded-lg shadow-md',
                                message.sender === 'bot'
                                    ? 'bg-indigo-100 text-gray-800 ml-2'
                                    : 'bg-indigo-600 text-white mr-2 ml-auto',
                            ]"
                        >
                            <div class="flex items-start">
                                <div
                                    v-if="message.sender === 'bot'"
                                    class="mr-2 text-indigo-600"
                                >
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div v-else class="mr-2 text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p class="text-sm">{{ message.text }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4">
                    <select
                        v-model="selectedQuestionId"
                        class="w-full mb-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Sélectionnez une question</option>
                        <option
                            v-for="question in questions"
                            :key="question.id"
                            :value="question.id"
                        >
                            {{ question.text }}
                        </option>
                    </select>
                    <button
                        @click="sendQuestion"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                    >
                        Envoyer
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isOpen: false,
            unreadMessages: 0,
            messages: [],
            selectedQuestionId: "",
            questions: [
                {
                    id: 1,
                    text: "Quels sont les horaires d'ouverture de l'application ?",
                    answer: "L'application est disponible 24/7.",
                },
                {
                    id: 2,
                    text: "Comment puis-je contacter le support technique ?",
                    answer: "Vous pouvez nous contacter par email à contact@ndiagandiaye.com ou par téléphone au +221 78 163 34 19.",
                },
                {
                    id: 3,
                    text: "Quelles sont les fonctionnalités de l'application ?",
                    answer: "L'application permet la gestion des bailleurs, des locataires, des propriétés, la facturation et bien plus encore.",
                },
                {
                    id: 4,
                    text: "Comment ajouter un nouveau bailleur ?",
                    answer: "Pour ajouter un nouveau bailleur, allez dans la section 'Bailleurs' et cliquez sur 'Ajouter un bailleur'.",
                },
                {
                    id: 5,
                    text: "Comment générer une facture ?",
                    answer: "Vous pouvez générer une facture en allant dans la section 'Facturation' et en cliquant sur 'Nouvelle facture'.",
                },
            ],
        };
    },
    methods: {
        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.unreadMessages = 0;
            }
        },
        sendQuestion() {
            const question = this.questions.find(
                (q) => q.id === this.selectedQuestionId
            );
            if (question) {
                this.addMessage(question.text, "user");
                setTimeout(() => {
                    this.addMessage(question.answer, "bot");
                }, 1000);
                this.selectedQuestionId = "";
            }
        },
        addMessage(text, sender) {
            this.messages.push({ text, sender });
            if (!this.isOpen) {
                this.unreadMessages++;
            }
        },
    },
    mounted() {
        setInterval(() => {
            if (!this.isOpen && this.unreadMessages === 0) {
                this.addMessage("Avez-vous besoin d'aide ?", "bot");
            }
        }, 30000);
    },
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

.bounce-enter-active {
    animation: bounce-in 0.5s;
}
.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.25);
    }
    100% {
        transform: scale(1);
    }
}

.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.3s ease;
}
.slide-fade-enter,
.slide-fade-leave-to {
    transform: translateY(20px);
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>
