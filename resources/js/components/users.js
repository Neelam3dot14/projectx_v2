import axios from 'axios';

const client = axios.create({
    baseURL: '/api',
});
client.interceptors.request.use(req => {
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
});
//client.defaults.timeout = 2000;
export default {   
    //Create New User
    async create(data) {
        return await client.post('admin/user/create', data);
    },    
    //Get All user
    async all(params) {
        return await client.get('admin/user/list', params);
    }, 
    //Get Specific user
    async find(id) {
        return await client.get(`admin/user/${id}`);
    },

    //Update Specific user
    async update(id, data) {
        return await client.put(`admin/user/${id}`, data);
    },

    //Delete Specific user
    async delete(id) {
        return await client.delete(`admin/user/${id}`);
    },
    
    //Impersonate Specific user
    async impersonate(id) {
        return await client.get(`admin/user/impersonate/${id}`);
    },
};
