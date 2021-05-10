import axios from 'axios';

const client = axios.create({
    baseURL: '/',
});

/*client.interceptors.request.use(req => {
    if(!req.headers.Authorization){
        const token = localStorage.getItem('accessToken');
        const impersonateToken = localStorage.getItem('impersonateToken');
        if(impersonateToken){
            req.headers.authorization = 'Bearer ' + impersonateToken;
        } else {
            req.headers.authorization = 'Bearer ' + token;
        }
    }
    return req;
});*/
//client.defaults.headers.common['Authorization'] = 'Bearer ' + token;
client.defaults.timeout = 5000;
export default {
    //Get All Campaigns
    async all(params) {
        return await client.get('campaigns', params);
    },

    //Get Specific Campaign
    async find(id) {
        return await client.get(`campaign/${id}`);
    },

    //Update Specific Campaign
    async update(id, data) {
        return await client.put(`campaign/${id}`, data);
    },

    //Delete Specific Campaign
    async delete(id) {
        return await client.delete(`campaign/${id}`);
    },

    //Create New Campaign
    async create(data) {
        return await client.post('campaign', data);
    },

    // Execute Specific Campaign
    async execute(id, data) {
        return await client.post(`campaign/${id}/execute`, data);
    },

    // Pause Specific Campaign
    async pause(id) {
        return await client.get(`campaign/${id}/pause`);
    },

    // Re-Activate Specific Campaign
    async reActivate(id) {
        return await client.get(`campaign/${id}/reactivate`);
    },

    // Execute Specific Campaign
    async export(id) {
        return await client.get(`campaign/${id}/export`, {timeout: 80000});
        // return await client.options(`campaign/${id}/export`, {
        //     responseType: 'arraybuffer',
        //     method: "get"
        // })
    },

    // Execute Specific Campaign
    async exportAll() {
        return await client.get(`campaigns/export`, {
            timeout: 80000
        });
    },

    // Execute Specific Campaign
    async exportTest(id) {
        return await client.get(`campaign/${id}/exporttest`);
    },

    // Execute Specific Campaign
    async exportAllTest() {
        return await client.get(`campaigns/export/all`, {
            timeout: 80000
        });
    },

    //Find Specific Keyword
    async findKeyword(id) {
        return await client.get(`/campaign/${id}/keyword`);
    },
};
