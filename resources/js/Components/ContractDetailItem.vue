<template>
    <div class="flex items-start bg-white p-4 rounded-lg shadow-sm">
        <div class="flex-shrink-0 mr-4">
            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                <i :class="['fas', `fa-${icon}`, 'text-indigo-600']"></i>
            </div>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">{{ label }}</p>
            <p class="text-base font-semibold text-gray-900">{{ value }}</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ContractDetailItem',
    props: {
        icon: {
            type: String,
            required: true
        },
        label: {
            type: String,
            required: true
        },
        value: {
            type: [String, Number],
            required: true
        }
    }
}
</script>

<style scoped>
/* Vous pouvez ajouter des styles spécifiques ici si nécessaire */
</style>
