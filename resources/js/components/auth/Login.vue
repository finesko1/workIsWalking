<template>
        <div class="max-w-2xl w-80 p-8 shadow-lg rounded-lg bg-gray-350">
            <h2 class="text-center text-2xl font-bold mb-6">Вход</h2>
            <form class="space-y-6" @submit.prevent="handleLogin">
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700">Логин</label>
                    <input id="login" type="text" v-model="form.login" placeholder="Введите логин или почту"
                           :class="{'border-red-500': errors.login}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
                    <span v-if="errors.login" class="text-red-500 text-sm">- {{ errors.login.join(' ') }}</span>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                    <input id="password" type="password" v-model="form.password" placeholder="Введите пароль"
                           :class="{'border-red-500': errors.login}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
                    <span v-if="errors.password" class="text-red-500 text-sm">- {{ errors.password.join(' ') }}</span>
                </div>

                <div>
                    <button type="submit"
                            class="w-full px-4 py-2 bg-cyan-600 text-white font-semibold rounded-lg shadow-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-opacity-50 transition hover:scale-95  duration-200 ease-in">
                        Войти
                    </button>
                </div>

                <router-link to="/signin" class="flex justify-center font-medium text-sm">
                    Нет аккаунта?
                    <button type="submit"
                                          class="ml-2 underline underline-offset-4 italic hover:scale-95 transform ease-in duration-200 hover:decoration-emerald-800">
                        Создать аккаунт
                    </button>
                </router-link>

                <router-link to="/forgotPassword" class="flex justify-center font-medium text-sm">
                    Забыли пароль?
                    <button type="submit"
                            class="ml-2 underline underline-offset-4 italic hover:scale-95 transform ease-in duration-200 hover:decoration-emerald-800">
                        Восстановить
                    </button>
                </router-link>
            </form>
        </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import { useUserStore } from "@/stores/user.js";
import { useFormStore } from '@/stores/formStore.js';

export default {
    name: 'Login',
    setup() {
        const userStore = useUserStore();
        const formStore = useFormStore();
        const form = ref({
            login: '',
            password: ''
        });
        const errors = ref({});

        onMounted(() => {
            const savedData = formStore.loadFormData(['login', 'password']);
            form.value = { ...form.value, ...savedData };
        });

        // Сохраняем данные формы при их изменении
        watch(form, (newValue) => {
            formStore.saveFormData(newValue);
        }, { deep: true });

        const handleLogin = async () => {
            try {
                errors.value = {};
                await userStore.login(form.value);
            } catch (e) {
                if (e.response && e.response.status === 422) {
                    errors.value = e.response.data.errors;
                    console.log(errors.value);
                } else {
                    console.error('Ошибка отправки данных: ', e);
                }
            }
        };

        return {
            form,
            errors,
            handleLogin,
        };
    },
    beforeRouteLeave(to, from, next) {
        const formStore = useFormStore();
        formStore.clearFormData(['login', 'password']); // Очищаем при смене URL
        next();
    }
};
</script>
