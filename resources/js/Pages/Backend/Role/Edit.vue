<template>
    <backend-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Role
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-5">
                        <div>
                            <jet-label for="name" value="Name" />
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                        </div>
                        <div>
                            <jet-label for="permission" value="Select Permissions" />
                            <multiselect id="permission" v-model="form.permissions" mode="tags" :options="permission"></multiselect>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Create Role
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
    import Multiselect from '@vueform/multiselect'

    export default {
        components: {
            BackendLayout,
            JetButton,
            JetInput,
            JetLabel,
            Multiselect,
        },
        props: ['permission','role'],
        data() {
            return {
                form: this.$inertia.form({
                    name: this.role.name,
                    permissions: this.role.permissions,
                })
            }
        },
        methods: {
            submit() {
                this.form.put(this.route('backend.role.update', this.role.id))
            }
        }
    }
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
