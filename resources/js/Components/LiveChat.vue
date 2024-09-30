<template>
    <div class="fixed bottom-4 right-4 w-80 bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-indigo-600 text-white p-4 flex justify-between items-center">
            <h3 class="font-bold">Chat en direct</h3>
            <button @click="$emit('close')" class="text-white hover:text-gray-200">
                <XMarkIcon class="h-6 w-6" />
            </button>
        </div>
        <div class="h-80 overflow-y-auto p-4 space-y-2">
            <div v-for="(message, index) in messages" :key="index" :class="{ 'text-right': message.sender === 'user' }">
                <span
                    :class="{ 'bg-indigo-100 rounded-lg p-2 inline-block': message.sender === 'user', 'bg-gray-100 rounded-lg p-2 inline-block': message.sender === 'agent' }">
                    {{ message.text }}
                </span>
            </div>
        </div>
        <div class="p-4 border-t">
            <form @submit.prevent="sendMessage">
                <div class="flex">
                    <input v-model="newMessage" type="text" placeholder="Tapez votre message..."
                        class="flex-grow border rounded-l-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-r-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    messages: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close', 'send']);

const newMessage = ref('');

const sendMessage = () => {
    if (newMessage.value.trim()) {
        emit('send', newMessage.value);
        newMessage.value = '';
    }
};
</script>
