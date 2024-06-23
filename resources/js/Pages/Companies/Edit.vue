<template>
    <AppLayout title="Modifier l'entreprise">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Modifier l'entreprise</h1>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom de
                                    l'entreprise</label>
                                <input type="text" id="name" v-model="form.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" v-model="form.email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                <input type="tel" id="phone" v-model="form.phone"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                                <textarea id="address" v-model="form.address" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700">Site web</label>
                                <input type="url" id="website" v-model="form.website"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" v-model="form.is_active"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Entreprise
                                    active</label>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Modifier l'entreprise
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

export default defineComponent({
    components: {
        AppLayout,
    },
    setup() {
        const { props } = usePage()
        const form = useForm({
            name: props.company.name || '',
            email: props.company.email || '',
            phone: props.company.phone || '',
            address: props.company.address || '',
            website: props.company.website || '',
            is_active: props.company.is_active || true,
        })

        const submit = () => {
            form.put(route('companies.update', props.company.id), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset()
                },
            })
        }

        return { form, submit }
    }
})
</script>
