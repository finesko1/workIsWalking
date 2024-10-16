<template>
    <form class="space-y-4 ml-8" v-if="isDataLoaded">
        <!-- Поле для фото профиля -->
        <div class="flex items-center relative group w-full">
            <label for="image_url" class="text-right pr-4">Фото профиля</label>
            <div class="w-full flex items-center">
                <div @click="selectFile" class="cursor-pointer rounded-full border-2 ring-2 ring-cyan-700 border-cyan-900">
                    <img v-if="profileData.image_url" :src="profileData.image_url" alt="User Image" class="rounded-full w-20 h-20 object-cover" />
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         class="w-16 h-16 text-white border border-cyan-900 bg-cyan-800 p-2 rounded-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <input
                    type="file"
                    id="image_url"
                    @change="onFileChange"
                    class="hidden"
                    ref="fileInput"
                />
            </div>
        </div>
        <span v-if="errors.image_url" class="text-red-600 text-sm">{{ errors.image_url[0] }}</span>

        <!-- Поле для логина -->
        <div class="flex items-center relative group w-full">
            <label for="login" class="text-right pr-4">Логин</label>
            <div class="flex flex-col w-full">
                <input
                    type="text"
                    id="login"
                    ref="login"
                    v-model="profileData.login"
                    :disabled="!isEditable.login"
                    class="p-2 mt-1 block px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                />
                <button
                    type="button"
                    class="absolute right-2 bottom-1 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                    @click="toggleEdit('login')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <title>Изменить</title>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.121 2.121 0 113 3L9 20.5H5.5v-3.5l11.732-11.732z" />
                    </svg>
                </button>
            </div>
        </div>
        <span v-if="errors.login" class="ml-16 text-red-600 text-sm">{{ errors.login[0] }}</span>


        <!-- Поле для почты -->
        <div class="flex items-center relative group w-full">
            <label for="email" class="text-right pr-4">Почта</label>
            <div class="flex flex-col w-full">
                <input
                    type="email"
                    id="email"
                    ref="email"
                    v-model="profileData.email"
                    :disabled="!isEditable.email"
                    class="p-2 mt-1 block px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                />
                <button
                    type="button"
                    class="absolute right-2 bottom-1 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                    @click="toggleEdit('email')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <title>Изменить</title>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.121 2.121 0 113 3L9 20.5H5.5v-3.5l11.732-11.732z" />
                    </svg>
                </button>
            </div>
        </div>
        <span v-if="errors.email" class="ml-16 text-red-600 text-sm">{{ errors.email[0] }}</span>

        <!-- Поле для изменения пароля -->
        <div class='flex'>
            <button type="button" class="underline text-blue-600 font-normal italic rounded transition" @click="isPasswordChanging = !isPasswordChanging">
                Сменить пароль
            </button>
        </div>

        <div v-if="isPasswordChanging" class="flex flex-col gap-2">
            <input
                type="password"
                id="password"
                v-model="profileData.password"
                class="p-2 mt-1 block px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                placeholder="Введите пароль"
            />
            <span v-if="errors.password" class="text-red-600 text-sm">{{ errors.password[0] }}</span>
            <input
                type="password"
                id="password_confirmation"
                v-model="profileData.password_confirmation"
                class="p-2 mt-1 block px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                placeholder="Подтвердите пароль"
            />
            <span v-if="errors.password_confirmation" class="text-red-600 text-sm">{{ errors.password_confirmation[0] }}</span>
        </div>

        <!-- Кнопка сохранить -->
        <div class="text-center">
            <button type="button" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 transition" @click="saveChanges">
                Сохранить
            </button>
        </div>
    </form>

    <div v-else class="flex ml-4 space-y-4 p-14 justify-center items-center w-full h-full">
        <button type="button" class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500 hover:bg-indigo-400 transition ease-in-out duration-150 cursor-not-allowed" disabled="">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Загрузка...
        </button>
    </div>
</template>

<script>
import router from '@/router'
import {showNotification} from "@/notifications.js";

export default {
    name: 'ProfileSettings',
    data() {
        return {
            profileData: {
                image_url: null,
                image_file: null,
                login: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            isDataLoaded: false,
            isPasswordChanging: false,
            isEditable: { login: false, email: false },
            errors: {}
        }
    },
    mounted() {
        this.fetchProfileData();
    },
    methods: {
        async fetchProfileData() {
            try {
                const response = await axios.get('/profile/profileSettings/show');
                await new Promise(resolve => setTimeout(resolve, 200));
                this.profileData = response.data.user;
                this.isDataLoaded = true;
            } catch (e) {
                showNotification('Ошибка загрузки данных профиля', 0, 3000);
            }
        },
        toggleEdit(fieldName) {
            if (this.isEditable.hasOwnProperty(fieldName)) {
                this.isEditable[fieldName] = !this.isEditable[fieldName];

                if (this.isEditable[fieldName]) {
                    this.$nextTick(() => {
                        const inputElement = this.$refs[fieldName];
                        if (inputElement) {
                            this.setCursorToEnd(inputElement);
                        }
                    });
                }
            }
        },
        setCursorToEnd(inputElement) {
            if (inputElement) {
                inputElement.focus();
                // Устанавливаем курсор в конец только для поддерживаемых полей
                if (inputElement.type === 'text' || inputElement.type === 'textarea') {
                    const value = inputElement.value;
                    inputElement.setSelectionRange(value.length, value.length);
                }
            } else {
                console.error('inputElement is undefined or not a valid input element');
            }
        },
        selectFile() {
            if (this.$refs.fileInput) {
                this.$refs.fileInput.click(); // Вызываем диалог выбора файла
            } else {
                console.error('fileInput ref is not defined or does not exist');
            }
        },
        onFileChange(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                // Создаем временный URL для изображения на странице
                this.profileData.image_file = file;
                this.profileData.image_url = URL.createObjectURL(file);
            } else {
                showNotification('Пожалуйста, загрузите изображение.', 0, 3000);
                this.profileData.image_file = null; // Сбрасываем значение, если файл не подходит
                this.profileData.image_url = null;
            }
        },
        async saveChanges() {
            try {
                this.errors = {};

                // Создаем новый объект FormData
                const formData = new FormData();
                // Добавляем все поля из profileData в FormData
                for (const key in this.profileData) {
                    formData.append(key, this.profileData[key]);
                }
                const response = await axios.post('/profile/profileSettings/update', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                showNotification(response.data.message, 1, 3000);
                await this.fetchProfileData();
                window.location.reload();
            } catch (e) {
                // Проверяем, есть ли ответ от сервера
                if (e.response) {
                    showNotification(e.response.data.error, 0, 3000);
                    if (e.response.status === 422) {
                        this.errors = e.response.data.errors;
                    }
                } else {
                    showNotification(e.message, 0, 3000);
                }
            }
        }
    }
}
</script>
