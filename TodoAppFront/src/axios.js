// src/axios.js
import axios from 'axios';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const axiosInstance = axios.create({
    baseURL: 'http://127.0.0.1:8000/',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
    },
});

if (csrfToken) {
    axiosInstance.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
} else {
    console.error('CSRF token not found');
}

axiosInstance.interceptors.request.use(config => {
    const token = localStorage.getItem('authToken');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default axiosInstance;
