<template>
    <form class="space-y-4 ml-8" v-if="isDataLoaded">
        <div class="flex items-center relative group">
            <label for="name" class="w-1/3 text-right pr-4">Логин:</label>
            <input
                type="text"
                id="name"
                ref="loginInput"
                v-model="profileData.login"
                :disabled="!isLoginEditable"
                class="w-2/3 p-2 border border-gray-300 rounded transition"
            />
            <button
                type="button"
                class="absolute right-2 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                @click="toggleEdit('login')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <title>Изменить</title>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.121 2.121 0 113 3L9 20.5H5.5v-3.5l11.732-11.732z" />
                </svg>
            </button>
        </div>

        <div class="flex items-center relative group">
            <label for="email" class="w-1/3 text-right pr-4">Почта:</label>
            <input
                type="email"
                id="email"
                ref="emailInput"
                v-model="profileData.email"
                :disabled="!isEmailEditable"
                class="w-2/3 p-2 border border-gray-300 rounded transition"
            />
            <button
                type="button"
                class="absolute right-2 bg-cyan-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition"
                @click="toggleEdit('email')"
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
export default {
    name: 'ProfileSettings',
    data() {
        return {
            profileData: {
                login: '',
                email: '',
            },
            isLoginEditable: false,
            isEmailEditable: false,
            isDataLoaded: false // Флаг для отслеживания загрузки данных
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
                this.profileData = response.data;
                this.isDataLoaded = true; // Устанавливаем флаг, когда данные загружены
            } catch (e) {
                console.error('Ошибка загрузки данных профиля', e);
            }
        },
        toggleEdit(field) {
            if (field === 'login') {
                this.isLoginEditable = !this.isLoginEditable;
                if (this.isLoginEditable) {
                    this.$nextTick(() => this.setCursorToEnd(this.$refs.loginInput));
                }
            } else if (field === 'email') {
                this.isEmailEditable = !this.isEmailEditable;
                if (this.isEmailEditable) {
                    this.$nextTick(() => this.setCursorToEnd(this.$refs.emailInput));
                }
            }
        },
        setCursorToEnd(inputElement) {
            inputElement.focus();
            // Устанавливаем курсор только для поддерживаемых типов
            if (inputElement.type === 'text' || inputElement.type === 'textarea') {
                const value = inputElement.value;
                inputElement.setSelectionRange(value.length, value.length);
            }
        },
        async saveChanges() {
            try {
                const response = await axios.post('/profile/profileSettings/update', this.profileData);
                console.log('Данные успешно обновлены:', response.data);
                alert('Данные успешно обновлены');
            } catch (e) {
                console.error('Ошибка при сохранении данных', e);
                alert('Ошибка при сохранении данных');
            }
        }
    }
}
</script>
