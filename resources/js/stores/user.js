// stores/user.js
import { defineStore } from "pinia";
import { ref } from 'vue';
import router from "@/router/index.js";

export const useUserStore = defineStore('user', () => {
    const user = ref(null);
    const isAuthenticated = ref(false);

    const login = async (loginData) => {
        try {
            const response = await axios.post('/login', loginData);
            user.value = response.data.user;
            localStorage.setItem('isAuthenticated', 'true');
            isAuthenticated.value = true;
            router.push('/');
        } catch (e) {
            localStorage.setItem('isAuthenticated', 'false');
            isAuthenticated.value = false;
            console.error('Ошибка при входе: ', e);
            router.push('/');
        }
    };

    const signin = async (signinData) => {
        try {
            const response = await axios.post('/signin', signinData);
            user.value = response.data.user;
            localStorage.setItem('isAuthenticated', 'true');
            isAuthenticated.value = true;
            console.log(response.data.user);
            router.push('/');
        } catch (e) {
            localStorage.setItem('isAuthenticated', 'false');
            isAuthenticated.value = false;
        }
    };

    const logout = async () => {
        try {
            await axios.post('/logout');
            user.value = null;
            localStorage.setItem('isAuthenticated', 'false');
            isAuthenticated.value = false;
            router.push('/');
        } catch (e) {
            localStorage.setItem('isAuthenticated', 'true');
            isAuthenticated.value = true;
            console.error('Ошибка при выходе: ', e);
        }
    };

    const checkAuth = async () => {
        try {
            const response = await axios.get('/user'); // получение данных о пользователе
            user.value = response.data;
            isAuthenticated.value = localStorage.getItem('isAuthenticated') === 'true';
            router.push('/');
        } catch (e) {
            user.value = null;
            localStorage.setItem('isAuthenticated', 'true');
            isAuthenticated.value = false;
        }
    };

    return { user, isAuthenticated, login, logout, checkAuth, signin };
});
