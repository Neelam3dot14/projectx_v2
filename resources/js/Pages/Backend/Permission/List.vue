<template>
    <backend-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Permissions
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.message">
                      <div class="flex">
                        <div>
                          <p class="text-sm">{{ $page.props.flash.message }}</p>
                        </div>
                      </div>
                    </div>
                    <button @click="onCreate($event)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Permission</button>
                    
                    <table class="table-fixed w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">ID</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Created At</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in permission">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.name }}</td>
                                <td class="border px-4 py-2">{{ row.created_at }}</td>
                                <td class="border px-4 py-2">
                                    <button @click.prevent="onEdit(row.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                    <button @click.prevent="onDelete(row.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
        props: ['permission'],
        data() {
            return {
                permission: this.permission,
            }
        },
        mounted: async function () {
               
        },
        methods: {
            async onCreate($event) {
                await this.$inertia.get(route('backend.permission.create'));
            },
            async onEdit(id, $event) {
                await this.$inertia.get(route('backend.permission.edit', id));
            },
            async onDelete(id, $event) {
                if (!confirm('Are you sure want to remove?')) return;
                await this.$inertia.delete(route('backend.permission.delete', id));
            },
        }
    }
</script>