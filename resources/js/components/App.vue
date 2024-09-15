<template>
    <div class="flex flex-col min-h-screen bg-neutral-300">
    <header class="rounded-t-lg top-0 left-0 w-full bg-cyan-800 p-4 flex justify-between items-center text-lg">
        <router-link to="/">
            <img src="/public/icons/wolf.png" alt="Main icon" class="w-14 h-14" />
        </router-link>
        <nav>
            <ul class="flex space-x-1 text-white justify-center items-center">
                <li class="flex group">
                    <router-link to="/"
                                 class="flex items-center px-5 py-2 rounded border border-cyan-950 hover:shadow-inner hover:border-cyan-800 transform transition-transform duration-200 ease-in w-36 h-12 group-hover:scale-95">
                        Главная
                        <img src="/public/icons/wolfWhite.png" alt="siteLogo" class="size-6 ml-2 hidden group-hover:block transition-opacity duration-200 ease-in" />
                    </router-link>
                </li>
                <li class="flex group items-center justify-center">
                    <router-link to="/login"
                                 class="px-5 py-2 rounded border border-cyan-950 hover:shadow-inner hover:border-cyan-800 transform transition-transform duration-200 ease-in w-36 h-12 group-hover:scale-95">
                        Вход
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ml-2 hidden group-hover:block transition-opacity duration-200 ease-in">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                    </router-link>
                </li>
                <li class="flex group">
                    <router-link to="/contacts"
                                 class="px-5 py-2 rounded border border-cyan-950 hover:shadow-inner hover:border-cyan-800 transform transition-transform duration-200 ease-in w-36 h-12 group-hover:scale-95">
                        Контакты
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ml-2 hidden group-hover:block transition-opacity duration-200 ease-in">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </router-link>
                </li>
            </ul>
        </nav>
    </header>

        <div class="flex body p-4 flex-1">
            <!-- Conditional rendering for centering Login component -->
            <div v-if="$route.name === 'Login' || $route.name === 'Register' || $route.name === 'EmailVerify' || $route.name === 'ForgotPassword'"
                    class="flex justify-center items-center flex-1">
                <router-view></router-view>
            </div>

            <!-- General rendering for other components -->
            <div v-else class="flex flex-1">
                <router-view></router-view>
            </div>
        </div>

    <footer class="rounded-b-lg footer border-t-4 border-cyan-950 text-lg">
        <router-link to="/">
            <div class="text-center p-4 bg-cyan-800 text-white">Разработка системы управления обучением &copy2024</div>
        </router-link>
    </footer>
    </div>
</template>

<script>
export default {
    methods: {
        login() {
            // Логика для перехода на страницу входа
        },
        register() {
            // Логика для перехода на страницу регистрации
        }
    }
}
</script>

<style scoped>
/* Стили для навигации и общей информации. Подключен Tailwind */
</style>
