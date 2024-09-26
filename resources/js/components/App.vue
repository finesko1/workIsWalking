<template>
    <div class="flex flex-col min-h-screen bg-neutral-300">
    <header class="rounded-t-lg top-0 left-0 w-full p-2 bg-cyan-800 flex justify-between items-center text-lg border-b-2 border-cyan-950">
        <router-link to="/">
            <img src="/public/icons/wolf.png" alt="Main icon" class="w-14 h-14" />
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
                            <img v-if="user.profilePhoto" :src="user.profilePhoto" alt="Profile photo"
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
                <li class="group relative active">
                    <router-link to="/"
                                 class="flex items-center justify-center pl-0 py-2 rounded border border-cyan-900 hover:shadow-inner hover:shadow-cyan-900 hover:border-cyan-800 transform transition-transform duration-300 delay-100 ease-in-out w-36 h-12 group-hover:scale-95">
                        <span class="transform group-hover:translate-x-[-5px] transition-transform duration-300 ease-in">
                            Главная
                        </span>
                        <img src="/public/icons/wolfWhite.png" alt="siteLogo" class="size-6 hidden group-hover:block delay-100 duration-300" />
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


        <div class="flex body p-1 flex-1">
            <div v-if="$route.name === 'Main' || $route.name === 'Root'"
                    class="flex items-center justify-center w-full">
                <router-view></router-view>
            </div>
            <!-- Conditional rendering for centering Login component -->
            <div v-else-if="$route.name === 'Login' || $route.name === 'Signin' || $route.name === 'EmailVerify' || $route.name === 'ForgotPassword'"
                    class="flex justify-center items-center flex-1">
                <router-view></router-view>
            </div>

            <div v-else class="flex flex-col w-full">
                <router-view class="flex-1"></router-view>
            </div>
        </div>


    <footer class="rounded-b-lg footer border-t-2 border-cyan-950 text-lg">
        <router-link to="/">
            <div class="text-center p-4 bg-cyan-800 text-white">Система управления обучением &copy2024</div>
        </router-link>
    </footer>
    </div>
</template>

<script>
import { ref, onMounted, computed, nextTick } from "vue";
import { useUserStore } from "@/stores/user.js";
import { showNotification, notificationState } from "@/notifications.js";
import { useRouterStore } from "@/stores/routerstore.js";
import router from '@/router';

export default {
    name: 'App',
    data() {
        return {
            user: {
                profilePhoto : '',
            },
        };
    },
    setup() {
        const userStore = useUserStore();
        const routerStore = useRouterStore();
        onMounted(async () => {
            // Сначала загружаем сохраненный маршрут
            const lastVisitedRoute = routerStore.lastVisitedRoute;
            // Проверяем аутентификацию пользователя
            await userStore.checkAuth();

            // Проверка, существует ли маршрут
            const routeExists = router.getRoutes().some(route => route.path === lastVisitedRoute);

            if (routeExists) {
                const pathSegments = lastVisitedRoute.split('/').filter(segment => segment); // Фильтруем пустые сегменты
                const mainRoute = `/${pathSegments[0]}`;
                let currentPath = mainRoute;

                // Переход на основной маршрут
                await router.push(mainRoute);

                // Переход на вложенные маршруты
                for (let i = 1; i < pathSegments.length; i++) {
                    const nestedRoute = pathSegments[i];
                    currentPath = `${currentPath}/${nestedRoute}`;
                    const nestedRouteExists = router.getRoutes().some(route => route.path === currentPath);

                    if (nestedRouteExists) {
                        await router.push(currentPath);
                    } else {
                        break; // Прерываем цикл, если маршрут не существует
                    }
                }
            } else {
                await router.push('/main');
            }
        });

        const handleLogout = async () => {
            try {
                routerStore.lastVisitedRoute = null;
                await userStore.logout();
            } catch (e) {
                console.error('Ошибка отправки данных: ', e);
            }
        };

        return {
            isAuthenticated: computed(() => userStore.isAuthenticated),
            handleLogout,
            showNotification,
            ...notificationState,
        };
    }
};
</script>

<style scoped>
/* Стили для навигации и общей информации. Подключен Tailwind */
</style>
