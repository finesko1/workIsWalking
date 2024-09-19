<template>
        <div class="max-w-2xl w-80 p-8 shadow-lg rounded-lg bg-gray-350">
            <h2 class="text-center text-2xl font-bold mb-6">Вход</h2>
            <form class="space-y-6" @submit.prevent="handleLogin">
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700">Логин</label>
                    <input id="login" type="text" v-model="form.login" placeholder="Введите логин или почту"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                    <input id="password" type="password" v-model="form.password" placeholder="Введите пароль"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
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
import { useUserStore } from '@/stores/user.js';
export default {
      name: 'Login',
      data() {
          return {
              form : {
                  login: '',
                  password: ''
              }
          }
      },
      methods: {
          async handleLogin() {
              const userStore = useUserStore();
              try {
                  await userStore.login(this.form);
              } catch (e) {
                  console.error('Ошибка отправки данных: ', e);
              }
          }
      }
  };
</script>
