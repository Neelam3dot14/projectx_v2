<template>
    <backend-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Roles
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
                    <button @click="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Roles</button>
                    
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
                            <tr v-for="row in data">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.name }}</td>
                                <td class="border px-4 py-2">{{ row.created_at }}</td>
                                <td class="border px-4 py-2">
                                    <button @click.prevent="edit(row)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                    <button @click.prevent="deleteRow(row)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        
                        <div class="fixed inset-0 transition-opacity">
                          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                          <form>
                          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="">
                                <div>
                                    <jet-label for="name" value="Name" />
                                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                                </div>
                                <div>
                                    <jet-label for="permission-list" value="Permission List" />
                                    <select v-model="form.permission" required multiple="true">
                                        <option value="">Select Permission</option>
                                        <option v-for="(value, key) in permissionList" :value="value">{{value}}</option>
                                    </select>
                                </div>
                                                                
                            </div>
                          </div>
                          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button @click.prevent="save(form)" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="!editMode">
                                Save
                              </button>
                            </span>
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button @click.prevent="update(form)" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="editMode">
                                Update
                              </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                              
                              <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancel
                              </button>
                            </span>
                          </div>
                          </form>
                          
                        </div>
                      </div>
                    </div>

                </div>
            </div>
        </div>
    </backend-layout>
</template>
<script>
    import BackendLayout from '@/Layouts/Backend/AppLayout'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetLabel from '@/Jetstream/Label'
    export default {
        components: {
            BackendLayout,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
        },
        props: ['data'],
        data() {
            return {
                editMode: false,
                isOpen: false,
                message: '',
                permissionList: [],
                form: {
                    name: '',
                    permission: '',
                },
            }
        },
        mounted: async function () {
            try{
                return await axios.get('/admin/permission/all')
                    .then(response => {
                        this.permissionList = response.data
                    });
            } catch(e){
                console.log(e)
            }          
        },
        methods: {
            openModal: async function () {
                this.isOpen = true;
                try{
                    return await axios.get('/admin/permission/all')
                    .then(response => {
                        this.permissionList = response.data
                    });
                } catch(e){
                    console.log(e)
                }
            },
            closeModal: async function () {
                this.isOpen = false;
                await this.reset();
                this.editMode=false;
            },
            reset: async function () {
                this.form = {
                    name: '',
                    permission: '',
                }
            },
            async save(data) {
                try{
                    await this.$inertia.post('/admin/role/create', data)
                    await this.reset();
                    await this.closeModal();
                    this.editMode = false;
                } catch(e) {
                    this.message = e.response.data.message || 'There was an issue creating the Role';
                }
            },
            async edit(data) {
                this.form = Object.assign({}, data);
                this.editMode = true;
                await this.openModal();
            },
            async update(data) {
                data._method = 'PUT';
                try{
                    let response = await this.$inertia.post('/admin/role/' + data.id, data)
                    //console.log(response);
                    await this.reset();
                    await this.closeModal();
                } catch(e) {
                    this.message = e.response.data.message || 'There was an issue creating the user.';
                }
            },
            deleteRow: async function (data) {
                if (!confirm('Are you sure want to remove?')) return;
                data._method = 'DELETE';
                await this.$inertia.post('/admin/role/' + data.id, data)
                await this.reset();
                await this.closeModal();
            }
        }
    }
</script>