import { ref, nextTick } from "vue";

const msgAlert = ref('');
const titleAlert = ref('');
const hasAlertWindow = ref(false);
const resultAlert = ref('');

export const showAlertWindow = (title, message) => {
    return new Promise((resolve) => {
        titleAlert.value = title;
        msgAlert.value = message;
        hasAlertWindow.value = true;

        // Сохраняем функцию разрешения промиса
        nextTick(() => {
            const onConfirm = () => {
                hasAlertWindow.value = false;
                resolve(true);
            };

            const onCancel = () => {
                hasAlertWindow.value = false;
                resolve(false);
            };

            document.querySelector('.alertWindow .confirm-button').onclick = onConfirm;
            document.querySelector('.alertWindow .cancel-button').onclick = onCancel;
        });
    });
};

export const alertWindowState = {
    hasAlertWindow,
    titleAlert,
    msgAlert,
    resultAlert
};
