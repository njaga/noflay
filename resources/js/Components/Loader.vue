<template>
    <div
        v-show="isLoading"
        class="fixed inset-0 flex items-center justify-center z-50 bg-indigo-100 bg-opacity-50"
    >
        <div class="loader">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const isLoading = ref(false);

router.on("start", () => (isLoading.value = true));
router.on("finish", () => (isLoading.value = false));
</script>

<style scoped>
.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 80px;
    height: 80px;
    position: relative;
}

.circle {
    width: 20px;
    height: 20px;
    background: linear-gradient(45deg, #8e44ad, #3F51B5);
    border-radius: 50%;
    position: absolute;
    animation: spin 1.5s linear infinite;
}

.circle:nth-child(1) {
    top: 0;
    left: 0;
    animation-delay: 0s;
}

.circle:nth-child(2) {
    top: 0;
    right: 0;
    animation-delay: 0.375s;
}

.circle:nth-child(3) {
    bottom: 0;
    right: 0;
    animation-delay: 0.75s;
}

.circle:nth-child(4) {
    bottom: 0;
    left: 0;
    animation-delay: 1.125s;
}

@keyframes spin {
    0%, 100% {
        transform: scale(0);
    }
    50% {
        transform: scale(1);
    }
}
</style>
