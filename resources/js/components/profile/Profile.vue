<template>
    <div class="flex w-full h-screen justify-center items-center">
        <!-- Контейнер, который подстраивается под содержимое -->
        <div class="bg-white shadow-md p-6 rounded-lg">
            <!-- Вверх: Заголовок меню -->
            <header class="text-center mb-6">
                <h2 class="text-lg font-semibold">Настройка профиля</h2>
            </header>

            <!-- Центр: Меню и форма -->
            <div class="flex justify-between">
                <!-- Секция меню -->
                <nav class="border-r-2 border-black">
                    <ul class="space-y-2">
                        <li>
                            <router-link to="/profile/profileSettings" class="block text-center bg-cyan-500 text-white p-2 rounded hover:bg-cyan-600 transition">
                                Профиль
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/profile/personalDataSettings" class="block text-center bg-cyan-500 text-white p-2 rounded hover:bg-cyan-600 transition">
                                Личные данные
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/profile/contactsSettings" class="block text-center bg-cyan-500 text-white p-2 rounded hover:bg-cyan-600 transition">
                                Контакты
                            </router-link>
                        </li>
                    </ul>
                </nav>

                <!-- Форма ввода данных -->
                <div>
                    <router-view></router-view>
                </div>
            </div>

            <!-- Низ: Дополнительная информация -->
            <footer class="text-center mt-6 text-sm text-gray-500">
                Дополнительная информация
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Profile',
        data() {
            return {

            }
        },
        methods: {

        }
    }
</script>
