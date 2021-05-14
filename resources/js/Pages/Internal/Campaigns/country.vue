<template>
    <internal-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Campaign
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <form @submit.prevent="submit" class="p-5">
                    <div>
                    <jet-label for="country" value="Country Name" />
                    <div class="output">Data: {{ form.country }}</div>
                    <Multiselect
                    v-model="form.country"
                    mode="tags"
                    placeholder="Select Country"
                    trackBy="canonical_country"
                    label="canonical_country"
                    :searchable=true
                    :options=countryList
                    @select=getGeoStateList()
                    >
                        <template v-slot:tag="{ option, handleTagRemove, disabled }">
                            <div class="multiselect-tag is-user">
                                {{ option.canonical_country }}
                                <i v-if="!disabled" @click.prevent @mousedown.prevent.stop="handleTagRemove(option, $event)" />
                            </div>
                        </template>
                    </Multiselect>
                    </div>
                    <div>
                        <jet-label for="states" value="Select States" />
                        <div class="output">Data: {{ form.states }}</div>
                        <Multiselect
                            v-model="form.states"
                            mode="tags"
                            placeholder="Select State"
                            trackBy="canonical_states"
                            label="canonical_states"
                            :searchable=true
                            :options=stateList
                            @search="getState"
                            :canDeselect=false
                            >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                    <div class="multiselect-tag is-user">
                                        {{ option.canonical_states }}
                                        <i v-if="!disabled" @click.prevent @mousedown.prevent.stop="handleTagRemove(option, $event)" />
                                    </div>
                                </template>
                        </Multiselect>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </internal-layout>
</template>
<script>
    import InternalLayout from '@/Layouts/Internal/AppLayout'
    import JetLabel from '@/Jetstream/Label'
    import Multiselect from '@vueform/multiselect'
    import geotarget from '../../../components/geotargets';
    export default {
        components: {
            InternalLayout,
            Multiselect,
            JetLabel
        },
        props: [''],
        data() {
            return {
                form: this.$inertia.form({
                    country: [],
                    states: [],
                }),
                countryList: [],
                stateList: [],
                queryData: null,
                isLoading: false,
            }
        },
        mounted: async function () {
            let country_response = await geotarget.getCountryList();
            this.countryList = country_response.data.data;
        },
        methods: {
            getGeoStateList: async function () {
                let code = [];
                await this.form.country.forEach(function(element) { 
                    console.log(element.value);
                    code.push(element.country_code);
                });
                let response = await geotarget.getDefaultStateList({code: code});
                this.stateList = response.data.data;
            },
            async getState(query){        
                if(query.length > 0){
                    this.queryData = JSON.parse (JSON.stringify (query));
                    let country_code = [];
                        this.form.country.forEach(function(element) { 
                        country_code.push(element.country_code);
                    });
                    try{
                        let response = await geotarget.searchStateByCountryCode({query : this.queryData, code: country_code});
                        this.stateList = response.data.data;
                    } catch(error) {
                        console.log(error)
                    }
                }
            },
        }
    }
</script>

<style src="@vueform/multiselect/themes/default.css"></style>