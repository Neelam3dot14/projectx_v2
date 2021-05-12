import axios from 'axios';

const client = axios.create({
    baseURL: '/',
});

client.defaults.timeout = 5000;
export default {
    
    async findAds(keyword) {
        return await client.get(`keyword/${keyword}/ads`);
    },

    async findKewordAds(keyword_group_id, id) {
        return await client.get(`keyword-group/${keyword_group_id}/keyword-ads/${id}`, {timeout: 40000});
    },

    //Find Ad Trace
    async findAdTraces(id) {
        return await client.get(`keyword/ads/${id}/trace`);
    },
};
