<template>
    <internal-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Campaign List
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if=message>{{ message }}</div>
                <div class="bg-white shadow-xl sm:rounded-lg">
                    <inertia-link :href="route('internal.campaign.create')">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">New Campaign</button>
                    </inertia-link>
                    <button @click.prevent="onExportAll($event)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3 ml-4">Export All</button>
                    <button @click.prevent="onBackgroundExportAll($event)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3 ml-4">Background Export All</button>
                    <div class="flex items-center">
                        <div class='overflow-x-auto w-full'>
                    <!--table-->
                               <table class='mx-auto max-w-4xl w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300'>
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Keywords
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Search Engine
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Devices
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Country
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            States
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Execution Interval
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Revision Count
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ad Competitions
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ad Hijacks
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="campaign in campaigns" :key="campaign.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ campaign.name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ campaign.keywords }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ campaign.search_engine }}</div>
                                            </td>
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ campaign.device }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ campaign.country }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ campaign.canonical_states }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ campaign.execution_interval }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ campaign.alert_revisions_count }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <a @click.prevent="onViewAdCompetitorCount(campaign.id, $event)">
                                                        {{ campaign.ad_competitors_count }}
                                                        <LinkIcon class="h-5 w-5 text-blue-500"/>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <a @click.prevent="onViewAdHijackCount(campaign.id, $event)">
                                                        {{ campaign.ad_hijacks_count }} <LinkIcon class="h-5 w-5 text-blue-500"/>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ campaign.status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <Menu as="div" class="relative inline-block text-left">
    <div>
      <MenuButton class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
        Actions
        <ChevronDownIcon class="-mr-1 ml-2 h-5 w-5" aria-hidden="true" />
      </MenuButton>
    </div>

    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
      <MenuItems class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
        <div class="py-1">
          <MenuItem v-slot="{ active }">
            <a @click.prevent="onView(campaign.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">View</a>
          </MenuItem>
          <MenuItem v-slot="{ active }">
            <a @click.prevent="edit(campaign.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Edit</a>
          </MenuItem>
          <MenuItem v-slot="{ active }">
            <a @click.prevent="onDelete(campaign.id)"  :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Delete</a>
          </MenuItem>
           <MenuItem v-slot="{ active }">
            <a @click.prevent="onExecute(campaign.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Execute</a>
          </MenuItem>
          <MenuItem v-slot="{ active }" v-if="campaign.status =='ACTIVE'">
            <a @click.prevent="onPause(campaign.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Pause</a>
          </MenuItem>
          <MenuItem v-slot="{ active }" v-if="campaign.status =='INACTIVE'">
            <a @click.prevent="onReActivate(campaign.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">ReActivate</a>
          </MenuItem>
           <MenuItem v-slot="{ active }">
            <a @click.prevent="onExport(campaign.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Export</a>
          </MenuItem>
           <MenuItem v-slot="{ active }">
            <a @click.prevent="onBackgroundExport(id, $event)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Background Export</a>
          </MenuItem>
        </div>
      </MenuItems>
    </transition>
  </Menu>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                           </div></div>
                    <!--end of table-->
                </div>
            </div>
        </div>
    </internal-layout>
</template>

<script>
    import InternalLayout from '@/Layouts/Internal/AppLayout'
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon, LinkIcon } from '@heroicons/vue/solid'
    import api from "../../../components/campaigns";

    export default {
        components: {
            InternalLayout,
            Menu,
            MenuButton,
            MenuItem,
            MenuItems,
            ChevronDownIcon,
            LinkIcon,
        },
        props: ['campaigns'],
        data() {
            return {
                message: null,
            }
        },
        methods:{
            async edit(id){
                await this.$inertia.get('/campaign/' + id);
            },
            async onExecute(id, $event) {
                try{
                    let response = await api.execute(id);
                    this.message = "Campaign added to execution queue!";
                } catch(e) {
                    console.log(e);
                    if(e.response && e.response.data){
                        this.message = e.response.data.message || 'There was an issue while exporting the campaign.';
                        var errorMessage = '';
                        for( var error in e.response.data.errors ){
                            errorMessage += "  "+e.response.data.errors[error];
                        }
                        this.message += ""+errorMessage;
                    }
                }
            },
            async onDelete(id, $event) {
                try{
                    let response = await api.delete(id);
                    this.message = response.data.msg;
                    setTimeout(() => location.reload(), 1000);
                } catch(e) {
                    console.log(e);
                    if(e.response && e.response.data) {
                        this.message = e.response.data.message || 'There was an issue while Deleting the campaign.';
                    }
                }
            },
            async onPause(id, $event) {
                try{
                    let response = await api.pause(id);
                    this.message = response.data.msg;
                    setTimeout(() => location.reload(), 1000);
                } catch(e) {
                    console.log(e);
                    if(e.response && e.response.data) {
                        this.message = e.response.data.message || 'There was an issue while Pausing the campaign.';
                    }
                }
            },
            async onReActivate(id, $event) {
                try{
                    let response = await api.reActivate(id);
                    this.message = response.data.msg;
                    setTimeout(() => location.reload(), 1000);
                } catch(e) {
                    console.log(e);
                    if(e.response && e.response.data) {
                        this.message = e.response.data.message || 'There was an issue while Re-Activating campaign.';
                    }
                }
            },
            async onDelete(id, $event) {
                try{
                    let response = await api.delete(id);
                    this.message = response.data.msg;
                    setTimeout(() => location.reload(), 1000);
                } catch(e) {
                    console.log(e);
                    if(e.response && e.response.data) {
                        this.message = e.response.data.message || 'There was an issue while Deleting the campaign.';
                    }
                }
            },
            async onExport(id, $event) {
                try{
                    let response = await api.export(id);
                    console.log(response);
                    let blob = new Blob([response.data], { type: 'text/csv' })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'export.csv'
                    link.click()
                } catch(e) {
                    console.log(e);
                    if(e.response && e.response.data){
                        this.message = e.response.data.message || 'There was an issue while executing the campaign.';
                        var errorMessage = '';
                        for( var error in e.response.data.errors ){
                            errorMessage += "  "+e.response.data.errors[error];
                        }
                        this.message += ""+errorMessage;
                    }
                }
            },
            async onExportAll(event) {
                try{
                    let response = await api.exportAll();
                    let blob = new Blob([response.data], { type: 'text/csv' })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'export.csv'
                    link.click()
                } catch(e) {
                    console.log(e);
                    if(e.response && e.response.data){
                        this.message = e.response.data.message || 'There was an issue while exporting the campaigns.';
                        var errorMessage = '';
                        for( var error in e.response.data.errors ){
                            errorMessage += "  "+e.response.data.errors[error];
                        }
                        this.message += ""+errorMessage;
                    }
                }
            },
            async onBackgroundExport(id, $event) {
                try{
                    let response = await api.backgroundExport(id);
                    this.message = response.data.msg;
                } catch(e) {
                    console.log(e);
                }
            },
            async onBackgroundExportAll($event) {
                try{
                    let response = await api.backgroundExportAll();
                    this.message = response.data.msg;
                } catch(e) {
                    console.log(e);
                    if(e.response && e.response.data) {
                        this.message = e.response.data.message || 'There was an issue generating Report.';
                    }
                }
            },
            async onView(campaign_id, $event){
                this.$inertia.get(route('internal.campaign.view', campaign_id));
            },

            async onViewAdCompetitorCount(campaign_id, $event){
                this.$inertia.get(route('internal.ads.competitor', campaign_id));
            },

            async onViewAdHijackCount(campaign_id, $event){
                this.$inertia.get(route('internal.ads.hijack', campaign_id));
            },
        }
    }
</script>
