<template>
    <internal-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Campaign
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-5">
            <div>
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <jet-label for="keyword" value="Keywords" />
                <jet-input id="keyword" type="text" class="mt-1 block w-full" v-model="form.keywords" required />
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <jet-label for="device" value="Device" />
                    <select id="device" class="mt-1 block w-full" v-model="form.device" multiple="true" required>
                        <option value="Desktop">Desktop</option>
                        <option value="Mobile">Mobile</option>
                        <option value="Tablet">Tablet</option>
                    </select>
                </div>
                <div>
                    <jet-label for="search_engine" value="Search Engine" />
                    <select id="device" class="mt-1 block w-full" v-model="form.search_engine" multiple="true" required>
                        <option value="google">Google</option>
                        <option value="yahoo">Yahoo</option>
                        <option value="bing">Bing</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <jet-label for="country" value="Country Name" />
                    <select id="country" class="mt-1 block w-full" v-model="form.country" multiple="true" onchange="getGeoStateList()" required>
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

            
            <div class="flex items-center justify-end mt-4">
                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create
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
        props: [''],
        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    keywords: '',
                    device: '',
                    search_engine: '',
                    execution_interval: 6,
                    geotarget_search: [],
                    country: [],
                    states: [],
                    crawler: [],
                    engine: ['google'],
                }),
                countryList: [],
                stateList: [],
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
            submit() {
                this.form.post(this.route('internal.campaign.store'))
            },
            async getData(query){        
                if(query.length > 0){
                    this.isLoading = true;
                    this.queryData = JSON.parse (JSON.stringify (query));
                    let country_code = [];
                        this.form.country.forEach(function(element) { 
                        country_code.push(element.country_code);
                    });
                    try{
                        let response = await geotarget.search({query : this.queryData, code: country_code});
                        this.stateList = response.data.data;
                        this.isLoading = false; 
                    } catch(error) {
                        this.isLoading = false;
                    } finally {
                        this.isLoading = false
                    }
                }
            },
            async getGeoStateList(){
                console.log('here');
                let code = [];
                this.form.country.forEach(function(element) { 
                    code.push(element.country_code);
                });
                let response = await geotarget.getDefaultValue({code: code});
                //this.campaign.country.country_code  for single country code
                this.stateList = response.data.data;
            }
        }
    }
</script>
