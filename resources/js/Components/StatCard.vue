<template>
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div :class="`flex-shrink-0 rounded-md p-3 ${bgColorClass}`">
                    <component :is="iconComponent" class="h-6 w-6 text-white" aria-hidden="true" />
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            {{ title }}
                        </dt>
                        <dd>
                            <div class="text-lg font-medium text-gray-900">
                                {{ value }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div v-if="link" class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <Link :href="link" class="font-medium text-cyan-700 hover:text-cyan-900">
                    Voir les détails
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Users, FileText, Home, UserCheck, DollarSign, CreditCard, ShoppingCart } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    value: {
        type: [Number, String],
        required: true
    },
    icon: {
        type: String,
        required: true
    },
    color: {
        type: String,
        default: 'bg-blue-500'
    },
    link: {
        type: String,
        default: null  // Rend le prop optionnel
    }
});

const iconComponent = computed(() => {
    const icons = {
        users: Users,
        'file-text': FileText,
        home: Home,
        'user-check': UserCheck,
        'dollar-sign': DollarSign,
        'credit-card': CreditCard,
        'shopping-cart': ShoppingCart,
        'lock': UserCheck  // Ajouté pour l'icône "lock"
    };
    return icons[props.icon] || Users;
});

const bgColorClass = computed(() => {
    return props.color;
});
</script>
