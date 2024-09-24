// resources/js/notification.js
import { ref, nextTick } from "vue";
import gsap from "gsap"; // Убедитесь, что gsap импортирован

const msgNotification = ref('');
const hasNotification = ref(false);

export const showNotification = (message) => {
    msgNotification.value = message;
    hasNotification.value = true;

    nextTick(() => {
        const notificationElement = document.querySelector('.notification');

        // Появление уведомления: поднимается снизу
        gsap.fromTo(notificationElement, { opacity: 0, y: -20 }, { opacity: 1, y: 0, duration: 0.3 });

        // Скрыть уведомление через 5 секунд
        setTimeout(() => {
            // Скрытие уведомления: поднимается вверх
            gsap.fromTo(notificationElement,
                { opacity: 1, y: 0 },
                { opacity: 0, y: -20, duration: 0.3, onComplete: () => {
                        hasNotification.value = false;
                    }}
            );
        }, 600); // 5000 мс = 5 секунд
    });
};

export const notificationState = {
    msgNotification,
    hasNotification,
};
