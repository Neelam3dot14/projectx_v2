<template>
    <internal-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               View Campaign
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg">
                    <!--table started-->
                    <div class="flex items-center">
                        <div class='overflow-x-auto w-full'>
                            <table class="min-w-full divide-y divide-gray-200 px-2">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Details</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white whitespace-nowrap">
                                    <tr>
                                        <td class="px-6 whitespace-nowrap">ID</td>
                                        <td class="px-6 whitespace-nowrap">{{ campaign.id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 whitespace-nowrap">keywords</td>
                                        <td class="px-6 whitespace-nowrap">{{ campaign.keywords }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 whitespace-nowrap">Device</td>
                                        <td class="px-6 whitespace-nowrap">{{ campaign.device }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 whitespace-nowrap">Search Engine</td>
                                        <td class="px-6 whitespace-nowrap">{{ campaign.search_engine }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 whitespace-nowrap">Execution Type</td>
                                        <td class="px-6 whitespace-nowrap">{{ campaign.execution_type }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 whitespace-nowrap">Google Country Code</td>
                                        <td class="px-6 whitespace-nowrap">{{ campaign.country }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 whitespace-nowrap">Google Domain</td>
                                        <td class="px-6 whitespace-nowrap">{{ campaign.google_domain }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 whitespace-nowrap">Revisions</td>
                                        <td class="px-6 whitespace-nowrap"> Total: {{ campaign.alert_revisions_count }} | Success: {{ campaign.success_revisions_count }} |
                                            Crawl Failed: {{ campaign.crawl_failed_revisions_count }} | Scrape Failed: {{ campaign.scraping_failed_revisions_count }} |
                                            Pending: {{ campaign.pending_revisions_count }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--table end-->
                    <!--table started-->
                    <div class="flex items-center py-6">
                        <div class='overflow-x-auto w-full'>
                            <table class="min-w-full divide-y divide-gray-200 px-2">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Expand
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Keyword
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
                                            Ads Found
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" v-for="keywordGroup in keywordGroups" :key="keywordGroup.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ keywordGroup.id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <a @click.prevent="onExpand(keywordGroup.id)"> <ChevronDoubleDownIcon class="h-5 w-5 text-blue-500"/></a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ keywordGroup.keyword }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ keywordGroup.search_engine }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ keywordGroup.device }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ keywordGroup.country_code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ keywordGroup.states }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ keywordGroup.keyword_main_ads_count }}</div>
                                        </td>
                                    </tr>
                                    <tr :id="'keyword_ads_' + keywordGroup.id" class="keyword hidden" >
                                        <td colspan="8">
                                        <table class="min-w-full divide-y divide-gray-200 px-2">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Ads
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Ad Type
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Ad Position
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Ad Count
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" v-for="keywordGroup in keywordGroups" :key="keywordGroup.id">
                                                <tr v-for="{id, keyword_id, keyword_group_id, keyword_ads_id, device, search_engine, ad_visible_link, ad_title, ad_text, ad_type, ad_position, ad_count } in keyword_ads" v-bind:key="id">
                                                    <td class="px-6 py-4 whitespace-wrap">
                                                        <div class="text-sm text-gray-900">
                                                            <div class="ads">
                                                                <p><b>Ad</b> {{ ad_visible_link }}</p>
                                                                <h4 class="py-2 md:text-lg xs:text-sm text-indigo-800 wrap">{{ ad_title }}</h4>
                                                                <div class="wrap">{{ ad_text }}</div>
                                                                <div class="font-bold float-left">Device: {{ device }}</div>
                                                                <div class="font-bold float-right">Search Engine: {{ search_engine }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ ad_type }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ ad_position }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            <button @click.prevent="onViewAdCount(keyword_group_id, id, $event)" class="bg-transparent text-blue-700 font-semibold hover:text-blue py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                {{ ad_count }}<LinkIcon class="h-5 w-5 text-blue-500"/>
                                                            </button></div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                                                            <a @click.prevent="onViewTraces(id, keyword_id, $event)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm cursor-pointer']">View Traces</a>
                                                                        </MenuItem>
                                                                    </div>
                                                                </MenuItems>
                                                            </transition>
                                                        </Menu>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--end of table-->  
                </div>
            </div>
        </div>
    </internal-layout>
</template>
<script>
    import InternalLayout from '@/Layouts/Internal/AppLayout'
    import JetButton from '@/Jetstream/Button'
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import api from "../../../components/campaigns";
    import keywords_api from '../../../components/keywords';
    import { ChevronDoubleDownIcon, LinkIcon } from '@heroicons/vue/solid'
    export default {
        components: {
            InternalLayout,
            JetButton,
            Menu,
            MenuButton,
            MenuItem,
            MenuItems,
            ChevronDoubleDownIcon,
            LinkIcon,
        },
        props: ['campaign'],
        data() {
            return {
                campaign: this.campaign,
                keywordGroups:[],
                expand: false,
                keyword_ads: [],
                traced: false,
            }
        },
        async created() {
            try{
                let keyword_response = await api.findKeyword(this.campaign.id);
                this.keywordGroups = keyword_response.data.data;
            } catch(e){
                console.log(e)
            }
        },
        methods: {
            async onExpand(id, $event){
                try{
                    var keyCollection = document.getElementsByClassName('keyword');
                    for (var key of keyCollection) { 
                        key.style.display  = 'none';
                    }
                    var key_id =  document.getElementById('keyword_ads_' + id);
                    if (key_id.style.display == "none" || key_id.style.display == ''){
                        let keyword_ads_response = await keywords_api.findAds(id);
                        this.keyword_ads = keyword_ads_response.data.data;
                        this.expand = true;
                        key_id.style.display = 'table-row';
                    } else{
                        key_id.style.display = 'none';
                    }
                } catch(e){
                    console.log(e);
                }
            },
            async onViewAdCount(keyword_group_id, id, $event){
                this.$inertia.get(route('internal.keyword.adInstance.view', [ keyword_group_id, id ]));
            },
        }
    }
</script>