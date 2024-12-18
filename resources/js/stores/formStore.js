import { defineStore } from "pinia";
import CryptoJS from "crypto-js";
import { SECRET_KEY } from '../config.js';

export const useFormStore = defineStore('formStore', {
    state: () => ({
        formData: {},
    }),
    actions: {
        saveFormData(data) {
            this.formData = { ...this.formData, ...data };
            Object.keys(data).forEach(key => {
                let value = data[key];
                if (key === 'password') {
                    // Шифруем пароль перед сохранением
                    try {
                        value = CryptoJS.AES.encrypt(value, SECRET_KEY).toString();
                        console.log('Зашифрованный пароль:', value);
                    } catch (error) {
                        console.error('Ошибка при шифровании пароля:', error);
                    }
                }
                localStorage.setItem(key, value);
            });
        },

        loadFormData(keys) {
            const loadedData = {};
            keys.forEach(key => {
                let value = localStorage.getItem(key);
                if (value) {
                    if (key === 'password') {
                        // Расшифровываем пароль при загрузке
                        try {
                            const bytes = CryptoJS.AES.decrypt(value, SECRET_KEY);
                            value = bytes.toString(CryptoJS.enc.Utf8);
                            console.log('Расшифрованный пароль:', value);
                        } catch (error) {
                            console.error('Ошибка при расшифровке пароля:', error);
                            value = '';
                        }
                    }
                    loadedData[key] = value;
                }
            });
            this.formData = loadedData;
            return loadedData;
        },

        clearFormDataForKey(key) {
            localStorage.removeItem(key);
        },

        clearFormData(keys) {
            keys.forEach(key => {
                localStorage.removeItem(key);
            });
            this.formData = {};
        },
    },
});
