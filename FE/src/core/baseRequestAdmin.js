import axios from "axios";
const apiUrl = "http://127.0.0.1:8000/api/";

export default {
    getHeader() {
        let token   =   window.localStorage.getItem('token_admin');
        if(token == null) {
            return {}
        }
        // console.log(token);
        return { Authorization: 'Bearer ' + token }
    },
    get(url) {
        return axios.get(apiUrl + url, {headers : this.getHeader()});
    },
    post(url, data) {
        return axios.post(apiUrl + url, data, {headers : this.getHeader()});
    },
    delete(url) {
        return axios.delete(apiUrl + url, {headers : this.getHeader()});
    },
    put(url, data) {
        return axios.put(apiUrl + url, data, {headers : this.getHeader()});
    },
}
