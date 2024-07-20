<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="handleClose" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-black/30 backdrop-blur-sm"
                    aria-hidden="true"
                />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95 rotate-2"
                        enter-to="opacity-100 scale-100 rotate-0"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100 rotate-0"
                        leave-to="opacity-0 scale-95 rotate-2"
                    >
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <DialogTitle
                                as="h3"
                                class="text-2xl font-bold leading-6 text-indigo-700 mb-4"
                            >
                                Détails de la Transaction
                            </DialogTitle>
                            <div class="mt-2 space-y-6">
                                <div
                                    class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg"
                                >
                                    <div class="flex-shrink-0">
                                        <svg
                                            class="h-12 w-12 text-indigo-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Montant
                                        </p>
                                        <p
                                            class="text-2xl font-bold text-gray-900"
                                        >
                                            {{
                                                formatCurrency(
                                                    transaction.amount
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Date
                                        </p>
                                        <p
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ formatDate(transaction.date) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Type
                                        </p>
                                        <p
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ transaction.type }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Description
                                    </p>
                                    <p class="text-lg text-gray-900">
                                        {{ transaction.description }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 transition-colors duration-200"
                                    @click="handleClose"
                                >
                                    Fermer
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, watch } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

const props = defineProps({
    show: Boolean,
    transaction: Object,
});

const emit = defineEmits(["close"]);

const transaction = ref({ ...props.transaction });

watch(
    () => props.show,
    () => {
        if (props.show) {
            transaction.value = { ...props.transaction };
        }
    }
);

function handleClose() {
    emit("close");
}

function formatCurrency(value) {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(value);
}

function formatDate(date) {
    return new Date(date).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}
</script>
