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
//client.defaults.timeout = 2000;
export default {      
    //Get All permission
    async all() {
        return await client.get('admin/permission/list');
    }, 
    //Get All Permission name
    async getAllPermissionName() {
        return await client.get('admin/permission/all');
    },     
    //Create New Permission
    async create(data) {
        return await client.post('admin/permission/create', data);
    },
    //Get Specific permission
    async find(id) {
        return await client.get(`admin/permission/${id}`);
    },

    //Update Specific permission
    async update(id, data) {
        return await client.put(`admin/permission/${id}`, data);
    },

    //Delete Specific permission
    async delete(id) {
        return await client.delete(`admin/permission/${id}`);
    },
};
