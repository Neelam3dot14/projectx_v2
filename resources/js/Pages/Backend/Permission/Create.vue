<template>
    <backend-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Permission
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-5">
                        <div>
                            <jet-label for="name" value="Name" />
                            <jet-input id="name" type="text" class="mt-1 block w-1/2" v-model="form.name" required autofocus autocomplete="name" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Create Permission
                            </jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </backend-layout>
</template>
<script>
    import BackendLayout from '@/Layouts/Backend/AppLayout'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            BackendLayout,
            JetButton,
            JetInput,
            JetLabel,
        },
        props: [''],
        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('backend.permission.store'))
            }
        }
    }
</script>
