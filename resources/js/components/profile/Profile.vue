<template>
    <div class="flex w-full h-screen justify-center items-center">
        <div class="shadow-lg bg-neutral-300 p-6 rounded-lg">
            <header class="text-center mb-6">
                <h2 class="text-lg font-semibold">{{ headForm }}</h2>
            </header>

            <div class="flex justify-between">
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
                    </ul>
                </nav>

                <div>
                    <router-view></router-view>
                </div>
            </div>

            <footer class="text-center mt-6 text-sm text-gray-500">
               <div>

               </div>
            </footer>
        </div>
    </div>
</template>

<script>
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router'

export default {
    name: 'Profile',
    setup() {
        const headForm = ref('');
        const route = useRoute();

        const updateHeadForm = () => {
            if (route.path === '/profile/profileSettings') {
                headForm.value = 'Настройка профиля';
            } else if (route.path === '/profile/personalDataSettings') {
                headForm.value = 'Настройка персональных данных';
            } else if (route.path === '/profile/contactsSettings') {
                headForm.value = 'Контакты';
            } else {
                headForm.value = 'Настройка';
            }
        };

        updateHeadForm();

        // Следим за изменением маршрута
        watch(() => route.path, () => {
            updateHeadForm();
        });

        return {
            headForm
        };
    },
}
</script>
