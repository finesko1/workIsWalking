import axios from 'axios';
window.axios = axios;

// Устанавливаем заголовок CSRF-токена для всех запросов
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found');
}
