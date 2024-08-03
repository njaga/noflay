<template>
    <div
        class="bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-300 hover:shadow-xl"
    >
        <div class="p-6">
            <div class="flex items-start">
                <div :class="`flex-shrink-0 rounded-full p-3 ${color}`">
                    <component
                        :is="iconComponent"
                        class="h-6 w-6 text-white"
                        aria-hidden="true"
                    />
                </div>
                <div class="ml-5 flex-1 min-w-0">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            {{ title }}
                        </dt>
                        <dd class="mt-1 flex flex-col">
                            <span
                                class="text-2xl font-semibold text-gray-900"
                                >{{ formattedValue }}</span
                            >
                            <span class="text-sm text-gray-500">{{
                                valuePrefix
                            }}</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { Lock, DollarSign, Home, ShoppingCart } from "lucide-vue-next";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: Number,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
    color: {
        type: String,
        required: true,
    },
    useKFormat: {
        type: Boolean,
        default: true,
    },
    valuePrefix: {
        type: String,
        default: "",
    },
});

const iconComponent = computed(() => {
    const icons = {
        lock: Lock,
        "dollar-sign": DollarSign,
        home: Home,
        "shopping-cart": ShoppingCart,
    };
    return icons[props.icon] || Lock;
});

const formatNumberWithK = (value) => {
    if (value >= 1000000) {
        return (value / 1000000).toFixed(1) + "M";
    } else if (value >= 1000) {
        return (value / 1000).toFixed(1) + "K";
    }
    return value.toString();
};

const formattedValue = computed(() => {
    return props.useKFormat
        ? formatNumberWithK(props.value)
        : props.value.toLocaleString();
});
</script>
