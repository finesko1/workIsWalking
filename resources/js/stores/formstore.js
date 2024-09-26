import { defineStore } from "pinia";

export const useFormStore = defineStore('formStore', {
    state: () => ({
        formData: {},
    }),
    actions: {
        saveFormData(data) {
            this.FormData = { ...this.FormData, ...data};
            Object.keys(data).forEach(key => {
                localStorage.setItem(key, data[key]);
            });
        },

        loadFormData(keys) {
            const loadedData = {};
            keys.forEach(key => {
                const value = localStorage.getItem(key);
                if(value) {
                    loadedData[key] = value;
                }
            });
            this.formData = loadedData;
            return loadedData;
        },

        clearFormData(keys) {
            keys.forEach(key => {
                localStorage.removeItem(key); // Удаляем данные из localStorage
            });
            this.formData = {}; // Очищаем state формы
        },
    },
});
