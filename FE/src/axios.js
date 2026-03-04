import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Add a request interceptor
instance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('key_admin');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add a response interceptor
instance.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            // Unauthorized - probably token expired
            localStorage.removeItem('key_admin');
            localStorage.removeItem('ho_ten_admin');
            localStorage.removeItem('hinh_anh_admin');

            // Redirect to login if not already there
            if (window.location.pathname !== '/') {
                // Let frontend router handle it if possible, otherwise hard reload
                window.location.href = '/';
            }
        }
        return Promise.reject(error);
    }
);

export default instance;
