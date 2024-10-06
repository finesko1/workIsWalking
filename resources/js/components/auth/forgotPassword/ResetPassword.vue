<template>
    <div class="flex body p-1 flex-1 justify-center items-center">
        <div class="flex justify-center items-center max-w-2xl w-full p-8 shadow-lg rounded-lg bg-gray-350">
            <div class="w-full">
                <h1 class="text-center text-2xl font-bold mb-6">Смена пароля</h1>
                <form @submit.prevent="handleResetPassword">
                    <!-- Поле для почты, автоматически заполненное из URL -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Почта</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            placeholder="Введите вашу почту"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            placeholder="Введите пароль"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Проверка пароля</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            placeholder="Подтвердите пароль"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                            required
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full px-4 py-2 bg-cyan-600 text-white font-semibold rounded-lg shadow-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-opacity-50 transition hover:scale-95 duration-200 ease-in"
                    >
                        Сменить пароль
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {ref} from 'vue';
import {useRouter} from "vue-router";
import { showNotification } from "@/notifications.js";

export default {
    name: 'ResetPassword',
    props: {
        token: String,
        email: String
    },
    setup(props) {
        const router = useRouter();
        const form = ref({
            email: props.email || '',
            password: '',
            password_confirmation: '',
        });
        const errors = ref({});
        const handleResetPassword = async () => {
            try {
                errors.value = null;
                form.value.token = props.token;

                await axios.post('/reset-password', form.value);

                showNotification('Пароль успешно изменён!');
                await router.push('/');
            } catch (e) {
                if (e.response) {
                    errors.value = e.response.data.errors;
                }
                showNotification('Ошибка изменения пароля!', 0, 1000);
                console.error(e);
            }
        };

        return {
            form,
            handleResetPassword
        };
    }
};
</script>
