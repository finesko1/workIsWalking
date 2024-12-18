<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 h-full">
        <div class="bg-white w-1/2 p-6 rounded-lg shadow-lg relative">
            <!-- Кнопка закрытия -->
            <button type="button" @click="$emit('close')" class="absolute top-2 right-3 text-gray-500 hover:text-gray-800">
                &#x2715;
            </button>
            <!-- Содержимое модального окна -->
            <h2 class="text-xl font-semibold mb-4">Создание группы</h2>
            <!-- Форма или другие элементы для создания группы -->
            <form class="flex-col space-y-2">
                <!-- Ваше содержимое -->
                <div class="flex justify-center">
                    <div class="flex p-1">Введите название группы:</div>
                    <input type="text" class="flex p-1 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-600" placeholder="Название группы" />
                </div>
                <div class="flex justify-center">
                    <button type="button" class="py-1 px-6 border bg-cyan-600 border-cyan-800 hover:bg-cyan-700 hover:shadow-inner hover:shadow-cyan-600 rounded-md hover:scale-95 hover:text-gray-200 transition-transform duration-400 ease-in-out">
                        Создать
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {onMounted} from "vue";

export default {
    name: 'GroupsCreate',
    setup() {

        onMounted( () => {

        });
    }
}
</script>
