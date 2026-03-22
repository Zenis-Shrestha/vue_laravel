import axios from 'axios';
import router from '../router';
import { loadingState } from '../stores/loading';

const api = axios.create({
  baseURL: '/api',  // Laravel API routes
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
  timeout: 10000,
});

// Request interceptor
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    // ✅ add this — tells Laravel who the sender is so toOthers() works
    if (window.Echo) {
        config.headers['X-Socket-Id'] = window.Echo.socketId();
    }

    return config;
});

// Response interceptor
api.interceptors.response.use(
  (response) => {
    loadingState.stop(); // 👈 add this
    return response;
  },
  (error) => {
    loadingState.stop(); // 👈 add this
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      router.push('/login');
    }
    return Promise.reject(error);
  }
);

export default api;
