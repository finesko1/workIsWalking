<template>
    <form class="space-y-4 ml-8" v-if="isDataLoaded">
        <div v-for="(field, index) in fields" :key="index" class="flex items-center relative group">
            <label :for="field.name" class="w-1/3 text-right pr-4">{{ field.label }}</label>
            <div v-if="field.name === 'image_url'" class="w-2/3 flex items-center">
                <div @click="selectFile" class="cursor-pointer rounded-full border-2 ring-2 ring-cyan-700 border-cyan-900">
                    <img v-if="profileData.image_url" :src="profileData.image_url" alt="User Image" class="rounded-full w-20 h-20 object-cover" />
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         class="w-16 h-16 text-white border border-cyan-900 bg-cyan-800 p-2 rounded-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <input
                    :type="field.type"
                    :id="field.name"
                    @change="onFileChange"
                    class="hidden"
                    ref="fileInput"
                />
            </div>
            <input
                v-if="field.name !== 'password' && field.name !== 'image_url' "
                :type="field.type"
                :name="field.name"
                :id="field.name"
                v-model="profileData[field.name]"
                :disabled="!field.editable"
                :ref="field.name"
                class="w-2/3 p-2 border border-gray-300 rounded transition"
            />
            <button
                v-if="field.name === 'password' "
                type="button"
                class="underline text-blue-600 font-normal italic p-2 rounded transition"
                @click="isPasswordChanging = !isPasswordChanging"
            >
                Сменить пароль
            </button>

            <button
                v-if="field.name !== 'password' && field.name !== 'image_url' "
                type="button"
                class="absolute right-2 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                @click="toggleEdit(field.name)"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <title>Изменить</title>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.121 2.121 0 113 3L9 20.5H5.5v-3.5l11.732-11.732z" />
                </svg>
            </button>
        </div>

        <div v-if="isPasswordChanging" class="flex flex-col gap-2">
            <div class="flex">
                <div class="w-1/3"></div>
                <div class="w-2/3 relative">
                    <input
                        type="password"
                        id="password"
                        v-model="profileData.password"
                        class="w-full p-2 border border-gray-300 rounded transition"
                        placeholder="Введите пароль"
                    />
                    <span v-if="errors.password" class="text-red-500 text-sm block mt-1 w-52 p-3">- {{ errors.password.join(' ') }}</span>
                </div>
            </div>

            <div class="flex">
                <div class="w-1/3"></div>
                <div class="w-2/3 relative">
                    <input
                        type="password"
                        id="password_confirmation"
                        v-model="profileData.password_confirmation"
                        class="w-full p-2 border border-gray-300 rounded transition"
                        placeholder="Подтвердите пароль"
                    />
                    <span v-if="errors.password_confirmation" class="text-red-500 text-sm block mt-1 w-52 p-3">- {{ errors.password_confirmation.join(' ') }}</span>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button
                type="button"
                class="bg-green-500 text-white p-2 rounded hover:bg-green-600 transition"
                @click="saveChanges"
            >
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
            fields: [
                { name: 'image_url', label: 'Фото профиля', type: 'file', editable: false },
                { name: 'login', label: 'Логин', type: 'text', editable: false },
                { name: 'email', label: 'Почта', type: 'email', editable: false },
                { name: 'password', label: 'Пароль', type: 'password', editable: false },
            ],
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
            const field = this.fields.find(f => f.name === fieldName);
            if (field) {
                field.editable = !field.editable;

                if (field.editable) {
                    this.$nextTick(() => {
                        const inputElement = this.$refs[field.name][0];
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
                // Устанавливаем курсор только для поддерживаемых типов
                if (inputElement.type === 'text' || inputElement.type === 'textarea') {
                    const value = inputElement.value;
                    inputElement.setSelectionRange(value.length, value.length);
                }
            } else {
                console.error('inputElement is undefined or not a valid input element');
            }
        },
        selectFile() {
            if (this.$refs.fileInput && this.$refs.fileInput.length > 0) {
                this.$refs.fileInput[0].click(); // Используем первый выбраннай файл
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
