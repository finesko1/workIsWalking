import { createRouter, createWebHistory } from 'vue-router';
import { onMounted } from 'vue';

import App from '../components/App.vue';
import Main from '../components/Main.vue';
import Contacts from '../components/Contacts.vue';

import Login from '../components/auth/Login.vue';
import Signin from '../components/auth/Signin.vue';
import EmailVerify from "../components/auth/EmailVerify.vue";
import ForgotPassword from '../components/auth/forgotPassword/ForgotPassword.vue';
import ResetPassword from "../components/auth/forgotPassword/ResetPassword.vue";

import Profile from "../components/profile/Profile.vue";
import ProfileSettings from "../components/settings/ProfileSettings.vue";
import PersonalDataSettings from "../components/settings/PersonalDataSettings.vue";

import Groups from "../components/messages/Groups.vue";

import Friends from "../components/friends/Friends.vue";

import {useUserStore} from "@/stores/user.js";
import { useRouterStore } from "@/stores/routerStore.js";

const routes = [
    {
        path: '/',
        name: 'Root',
        component: Main,
    },
    {
        path: '/main',
        name: 'Main',
        component: Main
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
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: ForgotPassword,
    },
    {
        path: '/reset-password/:token',
        name: 'ResetPassword',
        component: ResetPassword,
        props: (route) => ({
            token: route.params.token,
            email: route.query.email,
        })
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
        path: '/profile',
        name: 'Profile',
        component: Profile,
        beforeEnter: (to, from, next) => {
            const userStore = useUserStore();
            if (userStore.isAuthenticated) {
                next();
            } else {
                next('/');
            }
        },
        redirect: '/profile/profileSettings',
        children: [
            {
                path: '/profile/profileSettings',
                name: 'ProfileSettings',
                component: ProfileSettings
            },
            {
                path: '/profile/personalDataSettings',
                name: 'PersonalSettings',
                component: PersonalDataSettings
            },
        ]
    },
    {
        path: '/groups',
        name: 'Groups',
        component: Groups,
        beforeEnter: (to, from, next) => {
            const userStore = useUserStore();
            if (userStore.isAuthenticated) {
                next();
            } else {
                next('/');
            }
        },
    },
    {
        path: '/friends',
        name: 'Friends',
        component: Friends,
        beforeEnter: (to, from, next) => {
            const userStore = useUserStore();
            if (userStore.isAuthenticated) {
                next();
            } else {
                next('/');
            }
        },
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const routerStore = useRouterStore();
    if (to.path === '/') {
        routerStore.setLastVisitedRoute('/main');
    } else {
        routerStore.setLastVisitedRoute(to.path);
    }
    next();
});

export default router;
