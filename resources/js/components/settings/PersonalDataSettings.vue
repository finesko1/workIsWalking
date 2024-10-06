<template>
    <form class="space-y-4 ml-8" v-if="isDataLoaded">
        <div v-for="(field, index) in fields" :key="index" class="flex items-center relative group">
            <label :for="field.name" class="w-1/3 text-right pr-4">{{ field.label }}</label>
            <input
                :type="field.type"
                :name="field.name"
                :id="field.name"
                v-model="personalData[field.name]"
                :disabled="!field.editable"
                :ref="field.name"
                class="w-2/3 p-2 border border-gray-300 rounded transition"
            />

            <button
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
import {showNotification} from "@/notifications.js";
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
            fields: [
                { name: 'first_name', label: 'first_name', type: 'text', editable: false },
                { name: 'second_name', label: 'second_name', type: 'text', editable: false },
                { name: 'phone_number', label: 'phone_number', type: 'text', editable: false },
                { name: 'city', label: 'city', type: 'text', editable: false },
            ],
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
                this.personalData = response.data.personalData;
                this.isDataLoaded = true;
            } catch (e) {
                showNotification('Ошибка загрузки персональных данных', 0, 3000);
            }
        },
        toggleEdit(fieldName) {
            const field = this.fields.find(f => f.name === fieldName);
            if (field) {
                field.editable = !field.editable;

                if(field.editable) {
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
        async saveChanges() {
            try {
                this.errors = {};
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
        }
    }
}
</script>
