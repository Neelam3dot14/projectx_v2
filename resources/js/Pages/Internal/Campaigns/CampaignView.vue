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
                </div></div>
                </div>
            </div>
        </div>
    </internal-layout>
</template>
<script>
    import InternalLayout from '@/Layouts/Internal/AppLayout'
    import JetButton from '@/Jetstream/Button'

    export default {
        components: {
            InternalLayout,
            JetButton,
        },
        props: ['campaign'],
        data() {
            return {}
        },
    }
</script>