<template>
    <div class="relative flex justify-center items-center w-96">
        <div class="max-w-2xl w-full p-8 shadow-lg rounded-lg bg-gray-350">
            <form class="space-y-3" @submit.prevent="handleForgotPassword">
                <div class="flex items-center mb-5">
                    <router-link to="/login">
                        <button type='button' class="hover:text-cyan-700 focus:outline-none mx-2 flex items-center justify-center h-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                    </router-link>

                    <h2 class="text-xl font-bold leading-5 flex items-center h-6">Восстановление аккаунта</h2>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input type="email" id="email" v-model="form.email" placeholder="example@mail.ru" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 sm:text-sm hover:shadow-md" />
                </div>
                <div class="flex justify-center text-sm">
                    <button type="submit" class="m-2 p-1.5 bg-cyan-600 text-white rounded-md transition duration-200 ease-in hover:scale-95 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-opacity-50 ">
                        Отправить подтверждение
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import {showNotification} from "@/notifications.js";


    export default {
        name: 'ForgotPassword',
        setup() {
            const form = ref({
                email: '',
                token: '',
                message: '',
                error: '',
            });

            const handleForgotPassword = async () => {
                try {
                    form.value.message = '';
                    form.value.error = '';
                    const response = await axios.post('/forgot-password', {email: form.value.email});
                    showNotification(response.data.message, 1, 4000);
                } catch (e) {
                    if (e.response) {
                        showNotification(e.response.data.error, 0, 3000);
                    } else {
                        showNotification('Something went wrong. Please try again later.', 0, 4000);
                    }
                }
            }
            return {
                form,
                handleForgotPassword,

            }
        }
    };
</script>
