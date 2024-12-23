import axios from 'axios';
import Cookies from 'js-cookie';

window.axios = axios;

window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Получаем CSRF-токен из cookies
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

if (token) {
    window.axios.defaults.headers.common['X-XSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found');
}
