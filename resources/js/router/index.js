import { createRouter, createWebHistory } from 'vue-router';
import { onMounted } from 'vue';
import App from '../components/App.vue';
import Main from '../components/Main.vue';
import Contacts from '../components/Contacts.vue';
import Login from '../components/auth/Login.vue';
import Signin from '../components/auth/Signin.vue';
import ForgotPassword from '../components/auth/ForgotPassword.vue';
import EmailVerify from "../components/auth/EmailVerify.vue";
import NewPassword from "../components/auth/NewPassword.vue";
import {useUserStore} from "@/stores/user.js";

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
        beforeEnter: (to, from, next) => {
            const userStore = useUserStore();
            if (userStore.isAuthenticated) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/signin',
        name: 'Signin',
        component: Signin,
        beforeEnter: (to, from, next) => {
            const userStore = useUserStore();
            if (userStore.isAuthenticated) {
                next('/');
            } else {
                next();
            }
        }
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
