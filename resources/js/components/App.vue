<template>
    <div class="flex flex-col min-h-screen bg-neutral-300">
        <header class="rounded-t-lg top-0 left-0 w-full p-2 bg-cyan-800 flex justify-between items-center text-lg border-b-2 border-cyan-950">
            <router-link to="/">
                <img src="/public/icons/logo.png" alt="Main icon" class="w-14 h-14" />
            </router-link>
            <nav>
                <ul class="flex space-x-1 text-white justify-center items-center">
                    <li v-if="isAuthenticated" class="group relative">
                        <router-link to="/profile" class="flex items-center group transition-all delay-100 duration-300 ease-in">
                            <div class="flex items-center">
                                <div class="opacity-0 transform transition-transform ease-in-out translate-x-16 group-hover:translate-x-[-16] group-hover:opacity-100 duration-300">
                                    <span class="bg-cyan-800 text-white p-2 mr-0 border-r-0 rounded group-hover:shadow-inner group-hover:shadow-cyan-900 hover:border-cyan-800">Профиль</span>
                                </div>
                            </div>
                            <div class="w-14 h-14 rounded-full overflow-hidden group-hover:scale-95 transform transition-all delay-100 duration-300 ease-in relative">
                                <img v-if="user.image_url" :src="user.image_url" alt="Profile photo"
                                     class="w-full h-full object-cover" />
                                <div v-else class="flex items-center justify-center w-full h-full text-white border border-cyan-900 bg-cyan-800 p-2 rounded-full ease-in-out hover:shadow-inner hover:shadow-cyan-900 hover:border-cyan-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                         class="w-12 h-12 text-white group-hover:scale-90 transform transition-all delay-100 duration-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                            </div>
                        </router-link>
                    </li>
                    <li v-if="isAuthenticated" class="group relative">
                        <router-link to="/friendship" class="flex items-center group transition-all delay-100 duration-300 ease-in">
                            <div class="flex items-center justify-center h-14 overflow-hidden border border-cyan-900 group-hover:scale-95 transform transition-all delay-100 duration-300 ease-in relative hover:shadow-inner group-hover:border-cyan-800 group-hover:shadow-cyan-900 rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                     class="w-12 h-12 scale-75 text-white transform transition-all delay-100 duration-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>

                            </div>
                        </router-link>
                    </li>
                    <li v-if="isAuthenticated" class="group relative">
                        <router-link to="/groups" class="flex items-center group transition-all delay-100 duration-300 ease-in">
                            <div class="flex items-center justify-center w-14 h-14 overflow-hidden border border-cyan-900 group-hover:scale-95 transform transition-all delay-100 duration-300 ease-in relative hover:shadow-inner group-hover:border-cyan-800 group-hover:shadow-cyan-900 rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                     class="w-12 h-12 scale-75 text-white transform transition-all delay-100 duration-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                                </svg>
                            </div>
                        </router-link>
                    </li>
                    <li class="group relative active">
                        <router-link to="/"
                                     class="flex items-center justify-center pl-0 py-2 rounded border border-cyan-900 hover:shadow-inner hover:shadow-cyan-900 hover:border-cyan-800 transform transition-transform duration-300 delay-100 ease-in-out w-36 h-12 group-hover:scale-95">
                        <span class="transform group-hover:translate-x-[-5px] transition-transform duration-300 ease-in">
                            Главная
                        </span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hidden group-hover:block delay-100 duration-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                            </svg>

                            <!--                            <img src="/public/icons/whiteWolf.png" alt="siteLogo" class="size-6 hidden group-hover:block delay-100 duration-300" />-->
                        </router-link>
                    </li>
                    <li v-if="!isAuthenticated" class="group relative">
                        <router-link to="/login"
                                     class="flex items-center justify-center pl-0 py-2 rounded border border-cyan-900 hover:shadow-inner hover:shadow-cyan-900 hover:border-cyan-800 transform transition-transform duration-300 delay-100 ease-in-out w-36 h-12 group-hover:scale-95">
                        <span class="transform group-hover:translate-x-[-5px] transition-transform duration-300 ease-in">
                            Вход
                        </span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="size-6 hidden group-hover:block delay-100 duration-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </router-link>
                    </li>
                    <li class="group relative">
                        <router-link to="/contacts"
                                     class="flex items-center justify-center pl-0 py-2 rounded border border-cyan-900 hover:shadow-inner hover:shadow-cyan-900 hover:border-cyan-800 transform transition-transform duration-300 delay-100 ease-in-out w-36 h-12 group-hover:scale-95">
                        <span class="group-hover:translate-x-[-5px] delay-100 duration-300">
                            Контакты
                        </span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="size-6 hidden group-hover:block delay-100 duration-300">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </router-link>
                    </li>
                    <li v-if="isAuthenticated" class="group relative">
                        <button type="submit" @click="handleLogout"
                                class="flex items-center justify-center pl-0 py-2 rounded border border-cyan-900 hover:shadow-inner hover:shadow-cyan-900 hover:border-cyan-800 transform transition-transform duration-300 delay-100 ease-in-out w-36 h-12 group-hover:scale-95">
                        <span class="group-hover:translate-x-[-5px] delay-100 duration-300 group-hover:text-red-300">
                            Выйти
                        </span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="size-6 hidden group-hover:block delay-100 duration-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </svg>
                        </button>
                    </li>
                </ul>


                <!-- Уведомления -->
                <div v-if="hasSuccessNotification"
                     class="notification fixed inset-x-0 top-32 flex justify-center z-50">
                    <div class="text-green-700 border-green-700 ring-2 ring-green-600 p-2 w-1/6 max-w-md rounded-lg shadow-lg">
                        <p class="text-center text-md font-semibold">{{ msgNotification }}</p>
                    </div>
                </div>
                <div v-if="hasErrorNotification"
                     class="notification fixed inset-x-0 top-32 flex justify-center z-50">
                    <div class="text-red-700 border-red-700 ring-2 ring-red-600 p-2 w-1/6 max-w-md rounded-lg shadow-lg">
                        <p class="text-center text-md font-semibold">{{ msgNotification }}</p>
                    </div>
                </div>

            </nav>
        </header>


        <div class="flex body p-1 grow relative">
            <div v-if="$route.name === 'Main' || $route.name === 'Root'" class="flex items-center justify-center w-full">
                <router-view></router-view>
            </div>
            <div v-else-if="$route.name === 'Login' || $route.name === 'Signin' || $route.name === 'EmailVerify' || $route.name === 'ForgotPassword' || $route.name === 'ResetPassword'" class="flex justify-center items-center flex-1">
                <router-view></router-view>
            </div>
            <div v-else class="flex w-full">
                <router-view class="w-full"></router-view>
            </div>

            <!-- Окно подтверждения -->
            <div v-if="hasAlertWindow"
                 class="absolute alertWindow flex justify-center items-center w-full h-full">
                <div class="bg-white rounded-lg shadow-lg p-6 w-1/3 z-20">
                    <h2 class="text-lg font-semibold mb-4 underline underline-offset-8 decoration-2 decoration-solid">{{ titleAlert }}</h2>
                    <p class="mb-4">{{ msgAlert }}</p>
                    <div class="flex justify-end">
                        <button class="cancel-button bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Отмена</button>
                        <button class="confirm-button bg-blue-500 text-white px-4 py-2 rounded">Подтвердить</button>
                    </div>
                </div>
                <div class="fixed inset-0 bg-black opacity-50 z-10"></div>
            </div>
        </div>

        <footer class="rounded-b-lg footer border-t-2 border-cyan-950 text-lg">
            <router-link to="/">
                <div class="text-center p-4 bg-cyan-800 text-white">Модуль поддержки дистанционного обучения &copy2024</div>
            </router-link>
        </footer>
    </div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import { useUserStore } from "@/stores/user.js";
import { showNotification, notificationState } from "@/notifications.js";
import { useRouterStore } from "@/stores/routerStore.js";
import { useRouter } from 'vue-router';  // Import useRouter
import { showAlertWindow, alertWindowState } from "@/alertWindow.js";
export default {
    name: 'App',
    setup() {
        const userStore = useUserStore();
        const routerStore = useRouterStore();
        const user = ref({});
        const router = useRouter();

        const { msgNotification, hasSuccessNotification, hasErrorNotification } = notificationState;
        const { hasAlertWindow, titleAlert, msgAlert, resultAlert } = alertWindowState;

        onMounted(async () => {
            const lastVisitedRoute = routerStore.lastVisitedRoute;
            await new Promise(resolve => setTimeout(resolve, 1));

            const isResetPasswordRoute = router.currentRoute.value.name === 'ResetPassword' && router.currentRoute.value.params.token;
            if (isResetPasswordRoute) {
                return;
            }

            try {
                await userStore.checkAuth();
                user.value = userStore.user;

                const currentRoutePath = router.currentRoute.value.path;

                // Обновленное условие перенаправления
                const authRoutes = ['/', '/login', '/signin', '/forgot-password', '/reset-password'];
                const shouldRedirect = authRoutes.includes(currentRoutePath) && lastVisitedRoute;

                if (shouldRedirect) {
                    const routeExists = router.getRoutes().some(route => route.path === lastVisitedRoute);

                    if (routeExists) {
                        await router.push(lastVisitedRoute);
                    }
                }
            } catch (error) {
                // Обработка ошибок остается без изменений
                if (error.response) {
                    if (isResetPasswordRoute) {
                        showNotification('Восстановление пароля', 1, 3000);
                    } else {
                        await router.push(currentRoutePath);
                    }
                } else {
                    await router.push(currentRoutePath);
                }
            }
        });

        const handleLogout = async () => {
            try {
                routerStore.lastVisitedRoute = null;
                await userStore.logout();
                showNotification('Выход успешно выполнен!', 1, 1000);
                user.image_url = '';
            } catch (e) {
                showNotification('Выход не выполнен!', 0, 1000);
                console.error('Ошибка отправки данных: ', e);
            }
        };


        return {
            isAuthenticated: computed(() => userStore.isAuthenticated),
            handleLogout,
            user,
            msgNotification,
            hasSuccessNotification,
            hasErrorNotification,
            titleAlert,
            msgAlert,
            hasAlertWindow,
        };
    }
};
</script>

<style scoped>
/* Стили для навигации и общей информации. Подключен Tailwind */
</style>
