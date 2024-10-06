// stores/user.js
import { defineStore } from "pinia";
import { ref } from 'vue';
import router from "@/router/index.js";
import { showNotification, notificationState } from "@/notifications.js";

export const useUserStore = defineStore('user', () => {
    const user = ref(null);
    const isAuthenticated = ref(false);

    const login = async (loginData) => {
        try {
            const response = await axios.post('/login', loginData);
            user.value = response.data.user;
            localStorage.setItem('isAuthenticated', 'true');
            isAuthenticated.value = true;
            showNotification(response.data.message);
            router.push('/');
        } catch (e) {
            localStorage.setItem('isAuthenticated', 'false');
            isAuthenticated.value = false;
            if(e.response) {
                showNotification(e.response.data.error, 0)
            }
            throw(e);
        }
    };

    const signin = async (signinData) => {
        try {
            const response = await axios.post('/signin', signinData);
            user.value = response.data.user;
            localStorage.setItem('isAuthenticated', 'true');
            isAuthenticated.value = true;
            showNotification('Регистрация прошла успешно!');
            router.push('/');
        } catch (e) {
            localStorage.setItem('isAuthenticated', 'false');
            isAuthenticated.value = false;
            showNotification('Ошибка регистрации!', 0);
            throw(e);
        }
    };

    const logout = async () => {
        try {
            await axios.post('/logout');
            user.value = null;
            localStorage.setItem('isAuthenticated', 'false');
            isAuthenticated.value = false;
            showNotification('Выход успешно выполнен!');
            router.push('/');
        } catch (e) {
            localStorage.setItem('isAuthenticated', 'true');
            isAuthenticated.value = true;
            console.error('Ошибка при выходе: ', e);
            showNotification('Выход не выполнен', 0)
            throw(e);
        }
    };

    const checkAuth = async () => {
        try {
            const response = await axios.get('/user'); // получение данных о пользователе
            user.value = response.data.user;
            isAuthenticated.value = localStorage.getItem('isAuthenticated') === 'true';
        } catch (e) {
            user.value = null;
            localStorage.setItem('isAuthenticated', 'false');
            isAuthenticated.value = false;
            throw(e);
        }
    };

    return { user, isAuthenticated, login, logout, checkAuth, signin };
});
