<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { ChevronsUp, X } from 'lucide-vue-next';

const props = defineProps({
    show: Boolean,
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    primaryButton: {
        type: Object,
        default: null,
    },
    secondaryButton: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close', 'primaryAction', 'secondaryAction']);
const dialog = ref();
const showSlot = ref(props.show);
const modalContent = ref(null);

const animationStage = ref(0);

watch(() => props.show, (newValue) => {
    if (newValue) {
        document.body.style.overflow = 'hidden';
        showSlot.value = true;
        dialog.value?.showModal();
        startEntryAnimation();
    } else {
        startExitAnimation();
    }
});

const startEntryAnimation = () => {
    animationStage.value = 1;
    setTimeout(() => {
        animationStage.value = 2;
    }, 300);
};

const startExitAnimation = () => {
    animationStage.value = 3;
    setTimeout(() => {
        animationStage.value = 4;
        document.body.style.overflow = null;
        setTimeout(() => {
            dialog.value?.close();
            showSlot.value = false;
            animationStage.value = 0;
        }, 300);
    }, 300);
};

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const primaryAction = () => {
    emit('primaryAction');
};

const secondaryAction = () => {
    emit('secondaryAction');
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        'sm': 'sm:max-w-sm',
        'md': 'sm:max-w-md',
        'lg': 'sm:max-w-lg',
        'xl': 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});

const modalClasses = computed(() => {
    return [
        'fixed inset-0 z-50 flex items-center justify-center p-4',
        {
            'opacity-0': animationStage.value === 0,
            'opacity-100 transition-opacity duration-300': animationStage.value === 1,
            'opacity-100': animationStage.value === 2,
            'opacity-100 transition-opacity duration-300': animationStage.value === 3,
            'opacity-0': animationStage.value === 4,
        }
    ];
});

const contentClasses = computed(() => {
    return [
        'bg-white rounded-lg shadow-2xl overflow-hidden transform transition-all duration-300',
        maxWidthClass.value,
        {
            'translate-y-full scale-50': animationStage.value === 0,
            'translate-y-0 scale-100': animationStage.value === 1 || animationStage.value === 2,
            'translate-y-full scale-50 rotate-180': animationStage.value === 3 || animationStage.value === 4,
        }
    ];
});
</script>

<template>
    <Teleport to="body">
        <div v-show="show" :class="modalClasses">
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="close"></div>

            <div ref="modalContent" :class="contentClasses">
                <div class="relative">
                    <button v-if="closeable" @click="close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                        <X class="w-6 h-6" />
                    </button>

                    <div class="p-6">
                        <slot v-if="showSlot" />
                    </div>

                    <div v-if="primaryButton || secondaryButton" class="px-6 py-4 bg-gray-50 flex justify-end space-x-2">
                        <button
                            v-if="secondaryButton"
                            @click="secondaryAction"
                            class="px-4 py-2 bg-white text-gray-700 rounded-md border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            {{ secondaryButton.label }}
                        </button>
                        <button
                            v-if="primaryButton"
                            @click="primaryAction"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            {{ primaryButton.label }}
                        </button>
                    </div>

                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 mb-4">
                        <ChevronsUp class="w-6 h-6 text-gray-400 animate-bounce" />
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
