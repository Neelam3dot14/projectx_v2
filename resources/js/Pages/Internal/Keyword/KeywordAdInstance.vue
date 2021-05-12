<template>
    <internal-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               View Keyword Instances
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg">
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
                                            Alert Id
                                        </th>
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
                                            Trace Count
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Timestamp
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" v-for="{id, keyword_id, alert_id, device, search_engine, crawled_html, ad_visible_link, ad_title, ad_text, ad_type, ad_position, ad_traces_count, created_at} in keyword_ads" v-bind:key="id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ alert_id }}
                                                <a @click.prevent="onTraceExpand(id)"> <ChevronDoubleDownIcon class="h-5 w-5 text-blue-500"/></a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-wrap">
                                            <div class="text-sm text-gray-900">
                                                <p><b>Ad</b> {{ ad_visible_link }}</p>
                                                <h4 class="py-2 md:text-lg xs:text-sm text-indigo-800 wrap">{{ ad_title }}</h4>
                                                <div class="wrap">{{ ad_text }}</div>
                                                <div class="font-bold float-left">Device: {{ device }}</div>
                                                <div class="font-bold float-right">Search Engine: {{ search_engine }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ ad_type }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ ad_position }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ ad_traces_count }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ created_at }}</div>
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
                                                                <a @click.prevent="onPreview(alert_id, $event)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm cursor-pointer']">
                                                                    Preview Html <span> <ExternalLinkIcon class="h-5 w-5 text-blue-500"/></span>
                                                                    </a>
                                                            </MenuItem>
                                                        </div>
                                                    </MenuItems>
                                                </transition>
                                            </Menu>
                                        </td>
                                    </tr>
                                    <tr :id="'keyword_ads_' + id" class="traces hidden" >
                                        <td colspan="8">
                                        <table class="min-w-full divide-y divide-gray-200 px-2">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Ad Id
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Ad Traced Url
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Redirect Type
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Redirect
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="{id, ad_id, traced_url, redirect_type, context} in ad_traces" v-bind:key="id">
                                                    <td class="px-6 py-4 whitespace-wrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ ad_id }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 wrap">
                                                        <div class="text-sm text-gray-900">{{ traced_url }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ redirect_type }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ context }}</div>
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
    import { ExternalLinkIcon, ChevronDoubleDownIcon, LinkIcon } from '@heroicons/vue/solid'
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
            ExternalLinkIcon
        },
        props: ['keywordAds'],
        data() {
            return {
                keyword_ads: this.keywordAds,
                traceExpand: false,
                ad_traces: [],
            }
        },
        async created() {
        },
        methods: {
            async onTraceExpand(id, $event){
                try{
                    var keyCollection = document.getElementsByClassName('traces');
                    for (var key of keyCollection) { 
                        key.style.display  = 'none';
                    }
                    var key_id =  document.getElementById('keyword_ads_' + id);
                    if (key_id.style.display == "none" || key_id.style.display == ''){
                        let ad_trace_response = await keywords_api.findAdTraces(id);
                        this.ad_traces = ad_trace_response.data.data;
                        this.traceExpand = true;
                        key_id.style.display = 'table-row';
                    } else{
                        key_id.style.display = 'none';
                    }
                } catch(e){
                    console.log(e);
                }
            },
            async onPreview(alert_id, $event){
                this.$inertia.get(route('internal.keyword.html', alert_id))
            },
            async onBack() {
                this.$router.back();
            },
        }
    }
</script>