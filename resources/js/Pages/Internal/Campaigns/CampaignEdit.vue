<template>
    <internal-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               Edit Campaign
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-5">
            <div>
                <jet-label for="name" value="Campaign Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <jet-label for="keyword" value="Keywords (comma separated)" />
                <jet-input id="keyword" type="text" class="mt-1 block w-full" v-model="form.keywords" required />
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <jet-label for="device" value="Device" />
                    <select id="device" class="mt-1 block w-full" v-model="form.device" multiple="true" required>
                        <option v-for="device in deviceList" :value="device">{{ device }}</option>
                    </select>
                </div>
                <div>
                    <jet-label for="search_engine" value="Search Engine" />
                    <select id="device" class="mt-1 block w-full" v-model="form.search_engine" multiple="true" required>
                        <option v-for="search_engine in searchEngineList" :value="search_engine" :selected="form.search_engine == search_engine">{{ search_engine }}</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <jet-label for="country" value="Country Name" />
                    <select id="country[]" class="mt-1 block w-full" v-model="form.country" multiple v-on:change="getGeoStateList()" required>
                        <option v-for="country in countryList" :value="country">{{ country }}</option>
                    </select>
                </div>
                <div>
                    <jet-label for="states" value="Search Location" />
                    <select id="states" class="mt-1 block w-full" v-model="form.states" multiple>
                        <option v-for="state in stateList" :value="state">{{ state }}</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <jet-label for="execution_interval" value="Execution Interval" />
                <select id="execution_interval" class="mt-1 block w-full" v-model="form.execution_interval" required>
                    <option v-for="interval in frequency" :value="interval">{{ interval }}</option>
                </select>
            </div>

            <div class="mt-4">
                <jet-label for="device" value="Select Crawler" />
                <select id="crawler" class="mt-1 block w-full" v-model="form.crawler" multiple="true">
                    <option v-for="crawler in crawlerList" :value="crawler">{{ crawler }}</option>
                </select>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <jet-label for="blacklisted_domains" value="Blacklisted Domains (Line separated)" />
                    <textarea class="mt-1 block w-full" aria-describedby="blacklisted_domains" v-model="form.blacklisted_domain" id="campaign_blacklisted_domain" placeholder="Enter Blacklisted Domains (Line Separated)" rows="3"></textarea>
                </div>
                <div>
                    <jet-label for="whitelisted_domains" value="Whitelisted Domains" />
                    <textarea class="mt-1 block w-full" aria-describedby="whitelisted_domains" v-model="form.whitelisted_domain" id="campaign_whitelisted_domain" placeholder="Enter Whitelisted Domains (line Separated)" rows="3"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update Campaign
                </jet-button>
            </div>
        </form>
                </div>
            </div>
        </div>
    </internal-layout>
</template>
<script>
    import InternalLayout from '@/Layouts/Internal/AppLayout'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    import geotarget from '../../../components/geotargets';
    import api from '../../../components/campaigns'

    export default {
        components: {
            InternalLayout,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
        },
        props: ['campaign'],
        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    id: this.campaign.id,
                    name: this.campaign.name,
                    keywords: this.campaign.keywords,
                    device:  this.campaign.device,
                    search_engine: this.campaign.search_engine,
                    execution_interval: 6,
                    country: this.campaign.country,
                    states: this.campaign.canonical_states,
                    crawler: this.campaign.crawler,
                    blacklisted_domain: this.campaign.blacklisted_domain,
                    whitelisted_domain: this.campaign.whitelisted_domain,
                }),
                countryList: [],
                stateList: [],
                deviceList: ['Desktop', 'Mobile', 'Tablet'],
                searchEngineList: ['google', 'Yahoo', 'Bing'],
                frequency: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],
                crawlerList: ['scraperapi', 'scrapedog', 'proxy_crawl', 'webscraping_ai', 'scrapingbee'],
                crawlerOption: '1',
            }
        },
        mounted: async function () {
            /*if (!this.$session.exists()) {
                this.$router.push({ name: 'auth.login'})
            }*/
            
            let country_response = await geotarget.getCountryList();
            this.countryList = country_response.data.data;
        },
        methods: {
            getGeoStateList: async function () {
                let code = [];
                await this.form.country.forEach(function(element) { 
                    code.push(element.country_code);
                });
                let response = await geotarget.getDefaultValue({code: code});
                this.stateList = response.data.data;
            },
            submit() {
                this.form.post('/campaign/' + this.form.id)
            },
        },
    }
</script>
