<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-60 h-full">
        <div class="flex-col p-4 bg-white rounded-xl min-w-min relative">
            <header class="flex justify-center">
                <h2>
                    Создание раздела
                </h2>
                <button type='button'
                        @click="$emit('close')"
                        class='absolute top-2 right-3'>
                    &#x2715;
                </button>
            </header>
            <div class="p-4">
                <div class="flex gap-2 items-center">
                    <p>Введите название раздела:</p>
                    <input type="text" placeholder="раздел 1" v-model="sectionName"
                           class="p-1 border border-gray-200 rounded-md hover:border-gray-400"/>
                </div>
                <div class="flex justify-center mt-4">
                    <button
                        @click="sendSectionName"
                        class="bg-cyan-600 rounded-md py-2 px-4 hover:scale-95 transition-transform duration-200">
                        Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {ref} from "vue";

export default {
    name: 'AddSection',
    setup(props, {emit}) {
        const sectionName = ref('');
        const sendSectionName = () => {
            emit('getSectionName', sectionName.value)
        }

        return {
            sendSectionName,
            sectionName
        }
    }
}
</script>
