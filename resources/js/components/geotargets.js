import axios from 'axios';

const client = axios.create({
    baseURL: '/',
});
//passing token to all geotarget routes
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
client.defaults.timeout = 4000;

export default {
    async searchStateByCountryCode(data) {
        return await client.post('geotargets/state/search', data, {timeout: 8000});
    },
    async getDefaultStateList(data) {
        return await client.post('geotargets/statelist/default', data, {timeout: 8000});
    },
    async getCountryList() {
        return await client.get('geotargets/countrylist', {timeout: 20000});
    },
}
