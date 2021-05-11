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
                 <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ campaign.id }}</td>
                                </tr>
                                <tr>
                                    <td>keywords</td>
                                    <td>{{ campaign.keywords }}</td>
                                </tr>
                                <tr>
                                    <td>Device</td>
                                    <td>{{ campaign.device }}</td>
                                </tr>
                                <tr>
                                    <td>Search Engine</td>
                                    <td>{{ campaign.search_engine }}</td>
                                </tr>
                                <tr>
                                    <td>Execution Type</td>
                                    <td>{{ campaign.execution_type }}</td>
                                </tr>
                                <tr>
                                    <td>Google Country Code</td>
                                    <td>{{ campaign.country }}</td>
                                </tr>
                                <tr>
                                    <td>Google Domain</td>
                                    <td>{{ campaign.google_domain }}</td>
                                </tr>
                                <tr>
                                    <td>Revisions</td>
                                    <td> Total: {{ campaign.alert_revisions_count }} | Success: {{ campaign.success_revisions_count }} |
                                        Crawl Failed: {{ campaign.crawl_failed_revisions_count }} | Scrape Failed: {{ campaign.scraping_failed_revisions_count }} |
                                        Pending: {{ campaign.pending_revisions_count }}</td>
                                </tr>
                            </tbody>
                        </table>
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