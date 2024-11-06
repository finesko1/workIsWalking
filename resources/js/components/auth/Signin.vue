<template>
    <div class="flex justify-center items-center w-80">
        <div class="max-w-2xl w-full p-8 shadow-lg rounded-lg bg-gray-350">
            <h2 class="text-center text-2xl font-bold mb-6">Регистрация</h2>
            <form class="space-y-6" @submit.prevent="handleRegister">
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700">Логин</label>
                    <input id="login" type="text" v-model="form.login" placeholder="Введите логин"
                           :class="{'border-red-500 ring-2 ring-red-300 bg-red-100 focus:bg-white': errors.login}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
                    <span v-if="errors.login" class="text-red-500 text-sm">- {{ errors.login.join(' ') }}</span>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" v-model="form.email" placeholder="example@mail.ru"
                           :class="{'border-red-500 ring-2 ring-red-300 bg-red-100 focus:bg-white': errors.email}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
                    <span v-if="errors.email" class="text-red-500 text-sm">- {{ errors.email.join(' ') }}</span>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                    <input id="password" name="password" type="password" v-model="form.password" placeholder="Введите пароль"
                           :class="{'border-red-500 ring-2 ring-red-300 bg-red-100 focus:bg-white': errors.password}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
                    <span v-if="errors.password" class="text-red-500 text-sm">- {{ errors.password.join(' ') }}</span>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Проверка пароля</label>
                    <input id="password_confirmation" name="passwordConfirmation" type="password" v-model="form.password_confirmation" placeholder="Подтвердите пароль"
                           :class="{'border-red-500 ring-2 ring-red-300 bg-red-100 focus:bg-white': errors.password_confirmation}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
                    <span v-if="errors.password_confirmation" class="text-red-500 text-sm">- {{ errors.password_confirmation.join(' ') }}</span>
                </div>

                <div>
                    <button type="submit"
                            class="w-full px-4 py-2 bg-cyan-600 text-white font-semibold rounded-lg shadow-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-opacity-50 transition hover:scale-95  duration-200 ease-in">
                        Создать аккаунт
                    </button>
                </div>

                <router-link to="/login" class="flex justify-center font-medium text-sm">
                    Есть аккаунт?
                    <button type="submit"
                            class="ml-2 underline underline-offset-4 decoration-cyan-800 italic hover:scale-95 transition ease-in duration-200 hover:decoration-emerald-800">
                        Войти
                    </button>
                </router-link>
            </form>
        </div>
    </div>
</template>


<script>
    import { useUserStore } from "@/stores/user.js";
    import {onMounted, ref, watch} from "vue";
    import {useFormStore} from "@/stores/formStore.js";
    export default {
        name: 'Register',
        setup() {
            const formStore = useFormStore();
            const form = ref({
                login: '',
                email: '',
                password: '',
                password_confirmation: ''
            });
            const errors = ref({});


            onMounted(() => {
                const savedData = formStore.loadFormData(['login']);
                form.value.login = savedData.login;
            });

            const handleRegister = async () => {
                const userStore = useUserStore();
                try {
                    errors.value = {};
                    await userStore.signin(form.value);
                } catch (e) {
                    if(e.response && e.response.status === 422) {
                        errors.value = e.response.data.errors;
                    } else {
                        console.log('Ошибка отправки данных: ', e);
                    }
                }
            };


            watch(() => form.value.login, (newValue) => {
                if (errors.value.login) {
                    errors.value.login = '';
                }
            });

            watch(() => form.value.email, (newValue) => {
                if (errors.value.email) {
                    errors.value.email = '';
                }
            });

            watch(() => form.value.password, (newValue) => {
                if (errors.value.password) {
                    errors.value.password = '';
                }
            });

            watch(() => form.value.password_confirmation, (newValue) => {
                if (errors.value.password_confirmation) {
                    errors.value.password_confirmation = '';
                }
            });


            return {
                handleRegister,
                form,
                errors
            }
        },
    };
</script>
