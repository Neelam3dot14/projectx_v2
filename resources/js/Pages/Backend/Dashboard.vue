<template>
    <backend-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                    <h1> Welcome Admin !!</h1>
                </div>
            </div>
        </div>
    </backend-layout>
</template>

<script>
    import BackendLayout from '@/Layouts/Backend/AppLayout'
    import Welcome from '@/Jetstream/Welcome'

    export default {
        components: {
            BackendLayout,
            Welcome,
        },
    }
</script>
