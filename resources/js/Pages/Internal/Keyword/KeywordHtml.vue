<template>
    <div>
        <div v-if="!crawled_html">Loading...</div>
        <div v-else>
            <div v-html="crawled_html"></div>
        </div>
    </div>
</template>
<script>
    import InternalLayout from '@/Layouts/Internal/AppLayout'

    export default {
        components: {
            InternalLayout,
        },
        props: ['crawled_html'],
        data() {
            return {}
        }
    }
</script>