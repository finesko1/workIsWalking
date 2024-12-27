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
import FriendshipProfile from "../components/profile/FriendshipProfile.vue";
import ProfileSettings from "../components/profile/ProfileSettings.vue";
import PersonalDataSettings from "../components/profile/PersonalDataSettings.vue";

import Groups from "../components/messages/Groups.vue";

import Friendship from "../components/friends/Friendship.vue";
import Friends from "../components/friends/Friends.vue";
import SearchFriends from "../components/friends/SearchFriends.vue";
import Followers from "../components/friends/Followers.vue";
import Followings from "../components/friends/Followings.vue";
import Pendings from "../components/friends/Pendings.vue";
import Blocked from "../components/friends/Blocked.vue";

import { useUserStore } from "../stores/user.js";
import { useRouterStore } from "../stores/routerStore.js";

import GroupsView from "../components/messages/GroupsView/GroupsView.vue";
import GroupsCreate from "../components/messages/GroupsCreate.vue";
import GroupsEdit from "../components/messages/GroupsEdit/GroupsEdit.vue";
import AddTask from "../components/messages/GroupsEdit/AddTask.vue";
import AddSection from "../components/messages/GroupsEdit/AddSection.vue";
import AddEvent from "../components/messages/GroupsEdit/AddEvent.vue";
import GroupMembers from "../components/messages/GroupsView/GroupMembersInSection.vue"
import GroupView from "../components/messages/GroupsView/GroupView.vue";


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
        beforeEnter: async (to, from, next) => {
            const userStore = useUserStore();
            try {
                await userStore.checkAuth();
                if (userStore.isAuthenticated) {
                    next('/');
                } else {
                    next();
                }
            } catch (error) {
                //console.error("Ошибка при проверке аутентификации:", error.message);
                if (error.response.status === 401) {
                    next();
                } else {
                    next('/');
                }
            }
        },
    },
    {
        path: '/signin',
        name: 'Signin',
        component: Signin,
        beforeEnter: async (to, from, next) => {
            const userStore = useUserStore();
            try {
                await userStore.checkAuth();
                if (userStore.isAuthenticated) {
                    next('/');
                } else {
                    next();
                }
            } catch (error) {
                //console.error("Ошибка при проверке аутентификации:", error.message);
                if (error.response.status === 401) {
                    next();
                } else {
                    next('/');
                }
            }
        },
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
        beforeEnter: async (to, from, next) => {
            const userStore = useUserStore();
            try {
                await userStore.checkAuth();
                if (userStore.isAuthenticated) {
                    next();
                } else {
                    next('/');
                }
            } catch (error) {
                //console.error("Ошибка при проверке аутентификации:", error.message);
                if (error.response.status === 401) {
                    next('/');
                } else {
                    next();
                }
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
                name: 'PersonalDataSettings',
                component: PersonalDataSettings
            },
        ]
    },
    {
        path: '/profile/:userId',
        name: 'FriendshipProfile',
        component: FriendshipProfile,
        props: true,
        beforeEnter: async (to, from, next) => {
            const userStore = useUserStore();
            try {
                await userStore.checkAuth();
                if (userStore.isAuthenticated) {
                    next();
                } else {
                    next('/');
                }
            } catch (error) {
                //console.error("Ошибка при проверке аутентификации:", error.message);
                if (error.response.status === 401) {
                    next('/');
                } else {
                    next();
                }
            }
        },
    },
    {
        path: '/groups',
        name: 'Groups',
        component: Groups,
        beforeEnter: async (to, from, next) => {
            const userStore = useUserStore();
            try {
                await userStore.checkAuth();
                if (userStore.isAuthenticated) {
                    next();
                } else {
                    next('/');
                }
            } catch (error) {
                //console.error("Ошибка при проверке аутентификации:", error.message);
                if (error.response.status === 401) {
                    next('/');
                } else {
                    next();
                }
            }
        },
        children: [
            {
                path: '/groups/all',
                name: 'GroupsView',
                component: GroupsView
            },
            {
                path: '/groups/:groupId',
                name: 'GroupView',
                component: GroupView,
                props: true
            },
            {
                path: '/groups/create',
                name: 'GroupsCreate',
                component: GroupsCreate,
            },
            {
                path: '/groups/edit',
                name: 'GroupsEdit',
                component: GroupsEdit,
                children: [
                    {
                        path: '/groups/edit/addTaskInSections',
                        name:   'AddTask',
                        component: AddTask
                    },
                    {
                        path: '/groups/edit/addSectionInSections',
                        name:   'AddSection',
                        component: AddSection
                    },
                    {
                        path: '/groups/edit/addEventInSections',
                        name:   'AddEvent',
                        component: AddEvent
                    }
                ]
            },
            {
                path: '/groups/:groupId/users',
                name: 'GroupMembers',
                component: GroupMembers,
                props: true
            }
        ]
    },
    {
        path: '/friendship',
        name: 'Friendship',
        component: Friendship,
        beforeEnter: async (to, from, next) => {
            const userStore = useUserStore();
            try {
                await userStore.checkAuth();
                if (userStore.isAuthenticated) {
                    next();
                } else {
                    next('/');
                }
            } catch (error) {
                //console.error("Ошибка при проверке аутентификации:", error.message);
                if (error.response.status === 401) {
                    next('/');
                } else {
                    next();
                }
            }
        },
        // временно. добавить навигационное меню
        redirect: '/friendship/friends',
        children: [
            {
                path: '/friendship/friends',
                name: 'Friends',
                component: Friends
            },
            {
                path: '/friendship/search',
                name: 'SearchFriends',
                component: SearchFriends
            },
            {
                path: '/friendship/pendings',
                name: 'Pendings',
                component: Pendings
            },
            {
                path: '/friendship/followers',
                name: 'Followers',
                component: Followers
            },
            {
                path: '/friendship/followings',
                name: 'Followings',
                component: Followings
            },
            {
                path: '/friendship/blocked',
                name: 'Blocked',
                component: Blocked
            },
        ]
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
