<template>
    <form class="space-y-4 ml-8" v-if="isDataLoaded">
        <!-- First Name -->
        <div class="flex flex-col space-y-1">
            <div class="flex items-center relative group">
                <label for="first_name" class="w-1/3 text-left pr-20 whitespace-nowrap">Имя</label>
                <input
                    type="text"
                    name="first_name"
                    id="first_name"
                    v-model="personalData.first_name"
                    :disabled="!editableFields.first_name"
                    ref="first_name"
                    class="w-2/3 p-2 mt-1 block px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                />
                <button
                    type="button"
                    class="absolute right-2 bottom-1 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                    @click="toggleEdit('first_name')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <title>Изменить</title>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.121 2.121 0 113 3L9 20.5H5.5v-3.5l11.732-11.732z" />
                    </svg>
                </button>
            </div>
            <p v-if="errors.first_name" class="ml-32 text-red-500 text-sm">{{ errors.first_name[0] }}</p>
        </div>

        <!-- Last Name -->
        <div class="flex flex-col space-y-1">
            <div class="flex items-center relative group">
                <label for="second_name" class="w-1/3 text-left pr-20 whitespace-nowrap">Фамилия</label>
                <input
                    type="text"
                    name="second_name"
                    id="second_name"
                    v-model="personalData.second_name"
                    :disabled="!editableFields.second_name"
                    ref="second_name"
                    class="w-2/3 p-2 mt-1 block px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                />
                <button
                    type="button"
                    class="absolute right-2 bottom-1 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                    @click="toggleEdit('second_name')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <title>Изменить</title>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.121 2.121 0 113 3L9 20.5H5.5v-3.5l11.732-11.732z" />
                    </svg>
                </button>
            </div>
            <p v-if="errors.second_name" class="ml-32 text-red-500 text-sm">{{ errors.second_name[0] }}</p>
        </div>

        <!-- Phone Number -->
        <div class="flex flex-col space-y-1">
            <div class="flex items-center relative group">
                <label for="phone_number" class="w-1/3 text-left pr-20 whitespace-nowrap">Номер телефона</label>
                <input
                    type="text"
                    name="phone_number"
                    id="phone_number"
                    @input="handlePhoneNumberInput"
                    v-model="personalData.phone_number"
                    :disabled="!editableFields.phone_number"
                    ref="phone_number"
                    class="w-2/3 p-2 mt-1 block px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                    placeholder="+7-918-262-90-98"
                />
                <button
                    type="button"
                    class="absolute right-2 bottom-1 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                    @click="toggleEdit('phone_number')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <title>Изменить</title>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.121 2.121 0 113 3L9 20.5H5.5v-3.5l11.732-11.732z" />
                    </svg>
                </button>
            </div>
            <p v-if="errors.phone_number" class="ml-32 text-red-500 text-sm">{{ errors.phone_number[0] }}</p>
        </div>

        <!-- City -->
        <div class="flex flex-col space-y-1">
            <div class="flex items-center relative group">
                <label for="city" class="w-1/3 text-left pr-20 whitespace-nowrap">Город</label>
                <input
                    type="text"
                    name="city"
                    id="city"
                    v-model="personalData.city"
                    :disabled="!editableFields.city"
                    ref="city"
                    class="w-2/3 p-2 mt-1 block px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md"
                />
                <button
                    type="button"
                    class="absolute right-2 bottom-1 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                    @click="toggleEdit('city')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <title>Изменить</title>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.121 2.121 0 113 3L9 20.5H5.5v-3.5l11.732-11.732z" />
                    </svg>
                </button>
            </div>
            <p v-if="errors.city" class="ml-32 text-red-500 text-sm">{{ errors.city[0] }}</p>
        </div>

        <!-- Save Button -->
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
        <button type="button" class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-cyan-700 hover:bg-cyan-600 transition ease-in-out duration-150 cursor-not-allowed" disabled="">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Загрузка...
        </button>
    </div>
</template>


<script>
import { showNotification } from "@/notifications.js";

export default {
    name: 'PersonalDataSettings',
    data() {
        return {
            isDataLoaded: false,
            personalData: {
                first_name: '',
                second_name: '',
                phone_number: '',
                city: '',
            },
            editableFields: {
                first_name: false,
                second_name: false,
                phone_number: false,
                city: false,
            },
            errors: {}
        }
    },
    mounted() {
        this.fetchPersonalData();
    },
    methods: {
        async fetchPersonalData() {
            try {
                const response = await axios.get('/profile/personalData/show');
                await new Promise(resolve => setTimeout(resolve, 200));
                Object.assign(this.personalData, response.data.personalData);
                // this.personalData = response.data.personalData;
                if(this.personalData.phone_number) {
                    this.personalData.phone_number = this.formatPhoneNumberFromString(this.personalData.phone_number);
                }
                this.isDataLoaded = true;
            } catch (e) {
                showNotification('Ошибка загрузки персональных данных', 0, 3000);
            }
        },
        toggleEdit(fieldName) {
            this.editableFields[fieldName] = !this.editableFields[fieldName];
            this.$nextTick(() => {
                const inputElement = this.$refs[fieldName];
                if (inputElement && this.editableFields[fieldName]) {
                    this.setCursorToEnd(inputElement);
                }
            });
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
        async saveChanges() {
            try {
                this.errors = {};
                if (this.personalData.phone_number) {
                    this.personalData.phone_number = this.cleanPhoneNumber(this.personalData.phone_number) ?? null;
                }
                const response = await axios.post('/profile/personalData/update', this.personalData);
                showNotification(response.data.message, 1, 3000);
                await this.fetchPersonalData();
                window.location.reload();
            } catch (e) {
                // Проверяем, есть ли ответ от сервера
                if (e.response) {
                    showNotification(e.response.data.error, 0, 3000);
                    if (e.response.status === 422) {
                        this.errors = e.response.data.errors;
                        showNotification('Ошибка валидации', 0, 3000);
                    }
                } else {
                    showNotification('Ошибка сервера', 0, 3000);
                }
            }
        },
        formatPhoneNumberFromString(input) {
            // Удаляем все нецифровые символы
            input = input.replace(/\D/g, '');

            // Ограничиваем длину номера
            if (input.length > 11) {
                input = input.substring(0, 11);
            }

            let formatted = '';

            if (input.length > 0) {
                formatted = '+' + input[0];
            }

            if (input.length > 1) {
                formatted += '-' + input.substring(1, 4);
            }

            if (input.length > 4) {
                formatted += '-' + input.substring(4, 7);
            }

            if (input.length > 7) {
                formatted += '-' + input.substring(7, 9);
            }

            if (input.length > 9) {
                formatted += '-' + input.substring(9, 11);
            }

            return formatted;
        },

        // Метод для обработки события ввода
        handlePhoneNumberInput(event) {
            const inputValue = event.target.value;
            this.personalData.phone_number = this.formatPhoneNumberFromString(inputValue);
        },
        cleanPhoneNumber(phoneNumber) {
            return phoneNumber.replace(/\D/g, '');
        },
    }
}
</script>
