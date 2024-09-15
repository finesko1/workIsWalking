import { createRouter, createWebHistory } from 'vue-router';
import App from '../components/App.vue';
import Main from '../components/Main.vue';
import Contacts from '../components/Contacts.vue';
import Login from '../components/auth/Login.vue';
import Register from '../components/auth/Register.vue';
import ForgotPassword from '../components/auth/ForgotPassword.vue';
import EmailVerify from "../components/auth/EmailVerify.vue";
import NewPassword from "../components/auth/NewPassword.vue";

const routes = [
    {
        path: '/',
        name: 'Root',
        component: Main,
    },
    {
        path: '/main',
        name: 'Main',
        component: Main,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/forgotPassword',
        name: 'ForgotPassword',
        component: ForgotPassword,
    },
    {
        path: '/contacts',
        name: 'Contacts',
        component: Contacts
    },
    {
        path: '/emailVerify',
        name: 'EmailVerify',
        component: EmailVerify
    },
    {
        path: '/newPassword',
        name: 'NewPassword',
        component: NewPassword
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
