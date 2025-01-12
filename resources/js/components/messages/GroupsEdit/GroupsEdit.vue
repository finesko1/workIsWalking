<template>
    <div class="fixed inset-0 flex flex-wrap items-center justify-center bg-black bg-opacity-50 z-50 h-full">
        <div class="flex flex-col bg-white w-2/3 rounded-lg relative h-96 overflow-y-auto">
            <header class="flex justify-center sticky top-0 bg-neutral-200 p-1 items-center rounded-md mb-2">
                <button @click="$emit('close')" class="absolute top-2 right-3 text-gray-500 hover:text-gray-800">
                    &#x2715;
                </button>
                <h2 class="text-xl font-semibold mb-1 text-center">Редактирование группы</h2>
            </header>
            <div class="edit-content-body flex flex-col flex-grow space-y-4 w-full p-4">
<!--                Создание материалов группы-->
                <div class="edit-material-body flex flex-col flex-wrap break-words gap-1">
                    <div class="flex gap-2 group justify-between items-center edit-material-header-content-body"
                         @click="showMaterialSection = !showMaterialSection">
                        <p class='flex group-hover:text-green-700 group-hover:scale-90 transition-transform
                            duration-200 ease-in-out select-none'>
                            Материалы
                        </p>
                        <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.0" stroke="currentColor"
                                 class="flex size-7 text-cyan-900 group-hover:text-green-700 hover:scale-95 transition-transform group">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-col" v-if="showMaterialSection">
                        <ul class="edit-material-list">
                            <li v-for="(section, sectionIndex) in materialSections" :key="sectionIndex"
                                :class="['bg-gray-50 p-2 mx-4 my-2 rounded-md space-y-6',
                                section.isDelete ? 'italic line-through text-semibold' : '',
                                !section.isDelete && section.isNew ? 'text-green-900 font-semibold' : '']">
                                <div class="flex gap-1 items-center justify-between">
                                    <div class="edit-material-header">
                                        {{ section.sectionName }}:
                                    </div>
                                    <div class="flex items-center">
                                        <button type='button'
                                                @click="addMaterialHandler(sectionIndex)"
                                                class="text-gray-500 hover:text-gray-700 rounded-md p-0.5 text font-medium italic"
                                                :disabled="section.isDelete">
                                            добавить материалы
                                        </button>
                                        <input
                                            type="file"
                                            :ref="el => materialInputs[sectionIndex] = el"
                                            :disabled="section.isDelete"
                                            multiple
                                            @change="(event) => addMaterialFileHandler(sectionIndex, event)"
                                            style="display: none;"
                                        />
                                        <button v-if="section.isDelete" type="button" @click="section.isDelete = false">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                 class="size-6 hover:text-blue-700">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                            </svg>
                                        </button>
                                        <button v-else type="button" @click="section.isDelete = true">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                 class="size-6 hover:text-red-700 ml-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <ul class="ml-6 flex-col space-y-1 items-center bg-white p-2 mx-4 my-2 rounded-md">
                                    <li v-for="(material, materialIndex) in section.materials" :key="materialIndex"
                                        class="flex justify-between items-center hover:font-semibold">
                                        <div :class="['edit-material-content',
                                        material.isDelete ? 'italic line-through text-semibold' : '',
                                        !material.isDelete && material.isNew ? 'text-green-900 font-semibold' : '']">
                                            <a :href="material.file" :download="material.name" style="margin-left: 10px;">
                                                {{ material.name }}
                                            </a>
                                        </div>
                                        <button v-if="material.isDelete" type="button" @click="material.isDelete = false"
                                                :disabled="section.isDelete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                 class="size-6 hover:text-blue-700">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                            </svg>
                                        </button>
                                        <button v-else type="button" @click="material.isDelete = true"
                                                :disabled="section.isDelete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                 class="size-6 hover:text-red-700">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="flex justify-center">
                        <button type="button"
                                @click='addMaterialSectionForm = true'
                                v-if="showMaterialSection"
                                class="text-sm font-normal border-2 border-cyan-700 rounded-md p-1 m-1 bg-cyan-500 hover:scale-95 transition-transform ease-in-out">
                            Создать новый раздел
                        </button>
                    </div>
                </div>
<!--                Создание заданий в группе-->
                <div class="edit-tasks-body flex flex-col flex-wrap break-words gap-1">
                    <!-- Заголовок меню -->
                    <div
                        class="flex group justify-between items-center"
                        @click="showTaskSection = !showTaskSection">
                        <p class="group-hover:text-green-700 group-hover:scale-90 transition-transform duration-200 ease-in-out select-none">
                            Задания
                        </p>
                        <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.0" stroke="currentColor"
                                 class="flex size-7 text-cyan-900 group-hover:text-green-700 hover:scale-95 transition-transform group">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                    <!-- Секция с заданиями группы -->
                    <div>
                        <div v-if="showTaskSection" class="flex flex-col items-center justify-center transition-max-height duration-500 ease-in-out overflow-hidden">
                            <div class="w-full">
                                <div class="flex flex-col bg-white shadow-md rounded-lg">
                                    <!-- Список заданий -->
                                    <ul class="space-y-4">
                                        <li v-for="(section, sectionIndex) in taskSections" :key="sectionIndex"
                                            :class="['bg-gray-50 p-2 mx-4 my-2 rounded-md space-y-6',
                                                section.isDelete ? 'italic line-through text-semibold' : '',
                                               !section.isDelete && section.isNew ? 'text-green-900 font-semibold' : '']">
                                            <!-- Название раздела + действия -->
                                            <div class="flex justify-between items-center">
                                                <div class="text-lg font-semibold text-gray-800">
                                                    {{ section.sectionName }}
                                                </div>
                                                <div class="flex">
                                                    <!--Добавление материала в раздел-->
                                                    <div class="flex items-center ml-6">
                                                        <button type="button" title="Добавить материалы в раздел"
                                                            @click="addTaskHandler(sectionIndex)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                 class="hover:text-green-600 flex size-6 group-hover:text-green-700 hover:scale-95 transition-transform group">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                            </svg>
                                                        </button>
                                                        <input
                                                            type="file"
                                                            :disabled="section.isDelete"
                                                            :ref="el => taskInputs[sectionIndex] = el"
                                                            multiple
                                                            @change="(event) => addTaskFileHandler(sectionIndex, event)"
                                                            style="display: none;"
                                                        />
                                                    </div>
                                                    <!-- Удаление раздела -->
                                                    <div class="flex justify-end">
                                                        <button v-if="section.isDelete" type="button" title="Не удалять раздел"
                                                                @click="section.isDelete = false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                 class="w-6 h-6 hover:text-blue-700">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                                            </svg>
                                                        </button>
                                                        <button v-else type="button" title="Удалить раздел"
                                                                @click="section.isDelete = true">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                 class="w-6 h-6 hover:text-red-700 ml-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Материалы + действия -->
                                            <ul class="flex flex-col ml-4 space-y-4">
                                                <li v-for="(material, materialIndex) in section.materials" :key="materialIndex"
                                                    :class="['flex flex-col space-y-2 bg-white p-3 rounded-md shadow-sm hover:font-semibold',
                                                        material.isDelete ? 'italic line-through text-semibold' : '',
                                                        !material.isDelete && material.isNew ? 'text-green-900 font-semibold' : '']">
                                                    <div class="flex items-center justify-between">
                                                        <div class="text-gray-700">
                                                            <a :href="material.file" :download="material.name">
                                                                {{ material.name }}
                                                            </a>
                                                        </div>
<!--                                                        Удаление материала-->
                                                        <div class="flex items-center ml-6">
                                                            <button v-if="material.isDelete" type="button" :disabled="section.isDelete"
                                                                    @click="material.isDelete = false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                     class="w-6 h-6 hover:text-blue-700">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                                                </svg>
                                                            </button>
                                                            <button v-else type="button" :disabled="section.isDelete"
                                                                @click="material.isDelete = true">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                     class="w-6 h-6 hover:text-red-700">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Время сдачи задания -->
                                                    <div class="flex items-center justify-between text-sm text-gray-600 mt-2">
                                                        <div>Срок сдачи</div>
                                                        <div>
                                                            <span v-if="!material.deadline" @click="toggleDeadline(sectionIndex, materialIndex)" class="cursor-pointer text-blue-500 underline">
                                                                Без срока
                                                            </span>
                                                            <span v-else>
                                                                {{ formatDate(material.deadline) }}
                                                                <button type="button" @click="toggleDeadline(sectionIndex, materialIndex)" class="ml-2 text-red-500">
                                                                    Изменить
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!-- Календарь для выбора срока сдачи -->
                                                    <div v-if="material.showCalendar" class="mt-2">
                                                        <input type="date" v-model="material.newDeadline" />
                                                        <button type="button" @click="saveDeadline(sectionIndex, materialIndex)" class="ml-2 px-2 py-1 bg-green-500 text-white rounded">
                                                            Сохранить
                                                        </button>
                                                        <button type="button" @click="cancelDeadline(sectionIndex, materialIndex)" class="ml-2 px-2 py-1 bg-gray-300 text-gray-700 rounded">
                                                            Отмена
                                                        </button>
                                                    </div>
                                                    <!-- Перечень пользователей, которым адресовано задание -->
                                                    <div class="flex items-center justify-between text-sm text-gray-600">
                                                        <div>Пользователям</div>
                                                        <div>
                                                            <button type="button" @click="openAccessSettings(sectionIndex, materialIndex)"
                                                                    class="text-blue-500 hover:underline" :disabled="section.isDelete">
                                                                Настроить доступ
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Решения пользователей -->
                                                    <div class="flex items-center justify-between text-sm text-gray-600">
                                                        <div>Решения</div>
                                                        <div>
                                                            <button type="button" class="text-blue-500 hover:underline"
                                                                    @click="openTaskDetail(sectionIndex, materialIndex)"
                                                                    :disabled="section.isDelete">
                                                                Посмотреть
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- Создание нового раздела -->
                                    <div class="flex justify-center items-center">
                                        <button type="button"
                                                @click='addTaskSectionForm = true'
                                                v-if="showTaskSection"
                                                class="text-sm font-normal border-2 border-cyan-700 rounded-md p-1 m-1 bg-cyan-500 hover:scale-95 transition-transform ease-in-out">
                                            Создать новый раздел
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<!--                Создание мероприятий-->
                <div class="hidden edit-events-body">
                    <div class="flex group justify-between edit-events-header-content-body items-center"
                         @click="showEventsSection = !showEventsSection">
                        <p class='group-hover:text-green-700 group-hover:scale-90 transition-transform duration-200 ease-in-out select-none'>
                            Мероприятия
                        </p>
                        <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.0" stroke="currentColor"
                                 class="flex size-7 text-cyan-900 group-hover:text-green-700 hover:scale-95 transition-transform group">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                    <ul class="events-list" v-if="showEventsSection">
                        <li class="ml-6 flex-col">
                            <div class="flex justify-between hover:font-semibold">
                                <header>
                                    Мероприятие: <span class="font-semibold decoration-solid underline underline-offset-4">сбор</span>
                                </header>
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                         class="size-6 hover:text-red-700">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                            <div class="events-body-1 ml-6">
                                <div>
                                    Начало: <span class="font-semibold decoration-solid underline underline-offset-4">28 октября, 15:30</span>
                                </div>
                                <div class="">
                                    Указания: <span class="font-semibold decoration-solid underline underline-offset-4">всех жду без опозданий!</span>
                                </div>
                                <div class="flex-col">
                                    <div class="flex gap-1 items-center">
                                        <div class="events-material-header">
                                            Материалы:
                                        </div>
                                        <div>
                                            <button type='button' class="text-gray-500 hover:text-gray-700 rounded-md p-0.5 text font-medium italic">
                                                добавить материалы
                                            </button>
                                        </div>
                                    </div>
                                    <ul class="ml-6 flex-col space-y-1 items-center">
                                        <li class="flex justify-between items-center hover:font-semibold">
                                            <div>
                                                Документ 1
                                            </div>
                                            <button type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                     class="size-6 hover:text-red-700">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </li>
                                        <li class="flex justify-between items-center hover:font-semibold">
                                            <div>
                                                Документ 2
                                            </div>
                                            <button type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                     class="size-6 hover:text-red-700">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="flex justify-center">
                        <button type="button"
                                @click="addEventForm = true"
                                v-if="showEventsSection"
                                class="text-sm font-normal border border-2 border-cyan-700 rounded-md p-1 m-1 bg-cyan-500 hover:scale-95 transition-transform ease-in-out">
                            Создать мероприятие
                        </button>
                    </div>
                </div>
<!--                Включение чата группы-->
                <div class="hidden edit-messenger-body">
                    <div class="flex group justify-between edit-messenger-header-content-body items-center"
                         @click="showMessengerSection = !showMessengerSection">
                        <p class='group-hover:text-green-700 group-hover:scale-90 transition-transform duration-200 ease-in-out select-none'>
                            Мессенджер
                        </p>
                        <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.0" stroke="currentColor"
                                 class="flex size-7 text-cyan-900 group-hover:text-green-700 hover:scale-95 transition-transform group">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex ml-6 items-center gap-1 p-2" v-if="showMessengerSection">
                        <div>
                            Активировать чат группы
                        </div>
                        <div class="p-1">
                            <input type="checkbox" checked />
                        </div>
                    </div>
                </div>
<!--                Управление пользователями-->
                <div class="edit-users-body">
                    <div class="flex group justify-between edit-users-header-content-body items-center"
                         @click="showUsersSection = !showUsersSection">
                        <div class='group-hover:text-green-700 group-hover:scale-90 transition-transform duration-200 ease-in-out select-none'>
                            Пользователи - ({{ usersCount || 0 }})
                        </div>
                        <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.0" stroke="currentColor"
                                 class="flex size-7 text-cyan-900 group-hover:text-green-700 hover:scale-95 transition-transform group">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                    <div class="ml-6" v-if="showUsersSection">
                        <div>
                            <button type='button'
                                    @click="addUserForm = true"
                                    class="text-gray-500 hover:text-gray-700 rounded-md p-0.5 text font-medium italic">
                                Добавить пользователей
                            </button>
                        </div>
                        <div class="grid grid-cols-3">
                            <div class="italic font-semibold">
                                Пользователь
                            </div>
                            <div class="hidden col-span-2 italic font-semibold md:block">
                                Роль
                            </div>
                        </div>
                        <ul class="w-full">
                            <li v-for="(user, userIndex) in users" :key="userIndex"
                                class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-4 border-b hover:bg-gray-50">

                                <!-- Левый блок: Аватар и Имя пользователя -->
                                <div class="flex items-center">
                                    <img v-if="user.image_url" :src="user.image_url" alt="User Image"
                                         class="rounded-full w-12 h-12 object-cover" />
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                         class="w-12 h-12 min-w-12 text-white border border-cyan-900 bg-cyan-800 p-2 rounded-full">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    <div class="ml-4 p-2 hover:cursor-pointer hover:shadow-inner hover:shadow-neutral-500 hover:rounded-2xl hover:scale-95 transition-transform duration-200 ease-in-out" @click="open_homePage(user, userIndex)">
                                        {{ user.login || (user.first_name + ' ' + user.second_name) }}
                                        <span v-if="user.id === myUser.id">(Вы)</span>
                                    </div>
                                </div>

                                <!-- Средний блок: Роль пользователя -->
                                <div class="text-center md:text-left">
                                    <span v-if="user.role === 'admin'" class="text-red-600 font-medium">Руководитель</span>
                                    <span v-else-if="user.role === 'moderator'" class="text-yellow-500 font-medium">Помощник</span>
                                    <span v-else-if="user.role === 'user'" class="text-green-500 font-medium">Участник</span>
                                    <span v-else class="text-gray-700">{{ user.role }}</span>
                                </div>

                                <!-- Правый блок: Кнопки действий -->
                                <div class="flex justify-center md:justify-end gap-2">
                                    <button type="button"
                                            v-if="user.id !== myUser.id"
                                            @click="editRole(user)"
                                            class="hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button
                                        v-if="user.id !== myUser.id"
                                        @click="deleteUser(user.id)"
                                        type="button"
                                        class="hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                    <button
                                        v-else
                                        @click="deleteUser(user.id)"
                                        type="button"
                                        class="hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
<!--            Сохранение изменений-->
            <div class="flex-none flex justify-center">
                <button type="button"
                        class="p-1 text-md border-2 border-cyan-800 bg-cyan-600 rounded-md
                        hover:scale-95 transition-transform duration-200 m-4 items-center w-full"
                        @click='saveChanges'>
                    Сохранить изменения
                </button>
            </div>
            <AddTask v-if='addTaskForm' @close='addTaskForm = false'></AddTask>
            <AddSection v-if='addMaterialSectionForm' @getSectionName="addMaterialSectionHandler" @close='addMaterialSectionForm = false'></AddSection>
            <AddSection v-if='addTaskSectionForm' @getSectionName="addTaskSectionHandler" @close='addTaskSectionForm = false'></AddSection>
            <AddEvent v-if='addEventForm' @close='addEventForm = false'></AddEvent>
            <AddUser v-if='addUserForm' :groupid="currentGroupId" @close='closeAddUserForm'></AddUser>
            <EditUserRole v-if='showEditUserRoleForm' :groupid="currentGroupId" :user="currentUser" @close='closeEditRoleUserForm'></EditUserRole>
            <!--        Компонент просмотра выполнения заданий-->
<!--            showTaskDetail-->
            <TaskDetails v-if="showTaskDetail" @close="closeTaskDetail"
                         :groupId="currentGroupId" :sectionName="currentSectionName" :taskName="currentTaskName" :users="currentUsers">
            </TaskDetails>
            <!-- Модальное окно настройки доступа -->
            <div v-if="showAccessSettings" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-lg w-1/3">
                    <h3 class="text-lg font-semibold mb-4">Настройка доступа</h3>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="selectAllUsers" @change="toggleSelectAllUsers">
                            <span class="ml-2">Доступ для всех</span>
                        </label>
                    </div>
                    <div v-if="!selectAllUsers">
                        <h4 class="font-medium mb-2">Выберите пользователей</h4>
                        <div class="max-h-60 overflow-y-auto">
                            <label v-for="user in users" :key="user.id" class="flex items-center mb-2">
                                <input type="checkbox" v-model="selectedUsers" :value="user.id" class="mr-2">
                                <img v-if="user.image_url" :src="user.image_url" alt="User Image"
                                     class="rounded-full w-8 h-8 object-cover" />
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                     class="w-8 h-8 text-white border border-cyan-900 bg-cyan-800 p-1 rounded-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                {{ user.login || (user.first_name + ' ' + user.second_name) }}
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button @click="saveAccessSettings" class="px-4 py-2 bg-blue-600 text-white rounded-md mr-2">
                            Сохранить
                        </button>
                        <button @click="closeAccessSettings" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, ref} from "vue";
import AddTask from "@/components/messages/GroupsEdit/AddTask.vue";
import AddSection from "@/components/messages/GroupsEdit/AddSection.vue";
import AddEvent from "@/components/messages/GroupsEdit/AddEvent.vue";
import {showNotification} from "@/notifications.js";
import AddUser from "@/components/messages/GroupsEdit/AddUser.vue";
import {useUserStore} from "@/stores/user.js"
import {useRouter} from "vue-router";
import EditUserRole from "@/components/messages/GroupsEdit/EditUserRole.vue";
import TaskDetails from "@/components/messages/GroupsEdit/TaskDetails.vue";

export default {
    name: 'GroupsEdit',
    components: {
        EditUserRole,
        AddTask,
        AddSection,
        AddEvent,
        AddUser,
        TaskDetails
    },
    props: ['groupId'],
    setup(props, {emit}) {
        const currentGroupId = ref(props.groupId || localStorage.getItem('groupId'));
        // Для TaskDetails
        const currentSectionName = ref(null)
        const currentTaskName = ref(null)
        const currentUsers = ref([])
        // Ref-объекты для добавления файлов
        const materialInputs = ref([]);
        const taskInputs = ref([])
        // Материалы группы
        const materialSections = ref([]);
        const taskSections = ref([])
        // Пользователи группы
        const users = ref([])
        const usersCount = ref(0)
        // Выпадающие меню добавления разделов
        const addTaskForm = ref(false);
        const addMaterialSectionForm = ref(false);
        const addTaskSectionForm = ref(false);
        const addEventForm = ref(false);
        const addUserForm = ref(false);
        // Видимость секций
        const showMaterialSection = ref(false);
        const showTaskSection = ref(false);
        const showMessengerSection = ref(false);
        const showUsersSection = ref(false);
        const showEventsSection = ref(false);
        const showEditUserRoleForm = ref(false)
        const showTaskDetail = ref(false)
        const router = useRouter()
        // Редактируемый пользователь
        const currentUser = ref()
        const myUser = ref()
        const userStore = useUserStore()
        // Модальное окно настройки доступа
        const showAccessSettings = ref(false);
        const currentAccessSectionIndex = ref(null);
        const selectAllUsers = ref(true);
        const selectedUsers = ref([]);

        // Вызов меню выбора файлов-материалов
        const addMaterialHandler = (sectionIndex) => {
            if (materialInputs.value[sectionIndex]) {
                materialInputs.value[sectionIndex].click();
            }
        }// Вызов меню выбора файлов-заданий
        const addTaskHandler = (sectionIndex) => {
            if (taskInputs.value[sectionIndex]) {
                taskInputs.value[sectionIndex].click();
            }
        }
        // Обработка выбранных файлов в материалах
        const addMaterialFileHandler = (sectionIndex, event) => {
            const files = event.target.files;
            for (const file of files) {
                materialSections.value[sectionIndex].materials.push({
                    name: file.name,
                    file: file,
                    isNew: true,
                    isDelete: false
                });
            }
            event.target.value = null;
        };
        // Обработка выбранных файлов в задачах
        const addTaskFileHandler = (sectionIndex, event) => {
            const files = event.target.files;
            for (const file of files) {
                taskSections.value[sectionIndex].materials.push({
                    name: file.name,
                    file: file,
                    isNew: true,
                    isDelete: false
                });
            }
            event.target.value = null;
        };

        // Добавление раздела в материалы
        const addMaterialSectionHandler = async (materialSectionName) => {
            materialSections.value.push({
                sectionName: materialSectionName,
                materials: [],
                isNew: true,
                isDelete: false
            })
            addMaterialSectionForm.value = false
        }
        // Добавление раздела в задачи
        const addTaskSectionHandler = async (taskSectionName) => {
            taskSections.value.push({
                sectionName: taskSectionName,
                materials: [],
                isNew: true,
                isDelete: false,
                deadline: null,
                showCalendar: false,
                newDeadline: ''
            });
            addTaskSectionForm.value = false;
        };
        // Сохранение изменений в группе
        const saveChanges = async () => {
            try {
                const formData = new FormData();

                materialSections.value.forEach((section, sectionIndex) => {
                    if (section.materials && section.materials.length > 0) {
                        formData.append(`materialSections[${sectionIndex}][sectionName]`, section.sectionName);
                        formData.append(`materialSections[${sectionIndex}][isNew]`, section.isNew ? 1 : 0);
                        formData.append(`materialSections[${sectionIndex}][isDelete]`, section.isDelete ? 1 : 0);

                        section.materials.forEach((material, materialIndex) => {
                            formData.append(`materialSections[${sectionIndex}][materials][${materialIndex}][materialName]`, material.name);
                            formData.append(`materialSections[${sectionIndex}][materials][${materialIndex}][isNew]`, material.isNew ? 1 : 0);
                            formData.append(`materialSections[${sectionIndex}][materials][${materialIndex}][isDelete]`, material.isDelete ? 1 : 0);

                            if (material.file instanceof File) {
                                formData.append(`materialSections[${sectionIndex}][materials][${materialIndex}][file]`, material.file);
                            }
                        });
                    }
                });


                taskSections.value.forEach((section, sectionIndex) => {
                    if (section.materials && section.materials.length > 0) {
                        formData.append(`taskSections[${sectionIndex}][sectionName]`, section.sectionName);
                        formData.append(`taskSections[${sectionIndex}][isNew]`, section.isNew ? 1 : 0);
                        formData.append(`taskSections[${sectionIndex}][isDelete]`, section.isDelete ? 1 : 0);

                        section.materials.forEach((material, materialIndex) => {
                            formData.append(`taskSections[${sectionIndex}][materials][${materialIndex}][materialName]`, material.name);
                            formData.append(`taskSections[${sectionIndex}][materials][${materialIndex}][isNew]`, material.isNew ? 1 : 0);
                            formData.append(`taskSections[${sectionIndex}][materials][${materialIndex}][isDelete]`, material.isDelete ? 1 : 0);
                            formData.append(`taskSections[${sectionIndex}][materials][${materialIndex}][access_users]`, material.access_users || 'all');
                            formData.append(`taskSections[${sectionIndex}][materials][${materialIndex}][deadline]`, material.deadline || null);

                            if (material.file instanceof File) {
                                formData.append(`taskSections[${sectionIndex}][materials][${materialIndex}][file]`, material.file);
                            }
                        });
                    }
                });
                const response = await axios.post(`/group/${currentGroupId.value}/save`, formData);
                showNotification(response.data.message, 1, 1200);
                emit('close');
                await fetchGroupData();
            } catch (e) {
                if (e.response) {
                    showNotification(e.response.data.error, 0, 1500);
                    console.log(e.response.data.errors);
                } else {
                    console.log(e.message);
                }
            }
        };


        // Форматирование даты
        const formatDate = (date) => {
            if (!date) return 'Без срока';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('ru-RU', options);
        };

        // Переключение отображения календаря
        const toggleDeadline = (sectionIndex, materialIndex) => {
            const material = taskSections.value[sectionIndex].materials[materialIndex];
            material.showCalendar = !material.showCalendar;
            if (!material.showCalendar) {
                material.newDeadline = material.deadline;
            } else {
                material.newDeadline = material.deadline ? new Date(material.deadline).toISOString().substr(0, 10) : '';
            }
        };

        // Сохранение срока сдачи
        const saveDeadline = (sectionIndex, materialIndex) => {
            const material = taskSections.value[sectionIndex].materials[materialIndex];
            const selectedDate = material.newDeadline;
            if (selectedDate) {
                material.deadline = selectedDate;
                material.showCalendar = false;
            }
        };

        // Отмена выбора срока сдачи
        const cancelDeadline = (sectionIndex, materialIndex) => {
            const material = taskSections.value[sectionIndex].materials[materialIndex];
            material.showCalendar = false;
            material.newDeadline = material.deadline;
        };



        // Получение данных при редактировании группы
        const fetchGroupData = async () => {
            try {
                let time = new Date().getTime();
                const response = await axios.get(`/group/${currentGroupId.value}/getData?ts=${time}`);
                taskSections.value = response.data.taskSections.map(section => ({
                    ...section,
                    materials: section.materials.map(material => ({
                        ...material,
                        showCalendar: false,
                        newDeadline: material.deadline ? new Date(material.deadline).toISOString().substr(0, 10) : ''
                    }))
                })) || [];
                materialSections.value = response.data.materialSections
                users.value = response.data.usersData
                usersCount.value = response.data.countUsers
            } catch (e) {
                if (e.response) {
                    console.log(e.response.data);
                } else {
                    console.log(e.message);
                }
            }
        };

        // Изменение роли пользователя
        const editRole = async (user) => {
            try {
                currentUser.value = user
                showEditUserRoleForm.value = true
            } catch (e) {
                if (e.response && e.response.data) {
                    console.log(e.response.data)
                } else {
                    console.log(e.message)
                }
            }
        }
        // Закрытие окна изменения роли участника
        const closeEditRoleUserForm = async() => {
            showEditUserRoleForm.value = false
            currentUser.value = null
            await fetchGroupData()
        }

        // Удаление пользователя из группы
        const deleteUser = async (userId) => {
            try {
                await axios.delete(`/group/${currentGroupId.value}/${userId}/kickUser`)
                await fetchGroupData()
            } catch (e) {
                if (e.response && e.response.data) {
                    console.log(e.response.data)
                } else {
                    console.log(e.message)
                }
            }
        }

        // Открытие страницы пользователя
        const open_homePage = async (userId) => {
            //router.push({ name: 'FriendshipProfile', params: {userId: userId.id} });
            router.push('/profile/' + userId.id);
        }

        // Закрытие меню добавления пользователя
        const closeAddUserForm = async () => {
            addUserForm.value = false;
            await fetchGroupData()
        }


        // Открытие модального окна настройки доступа
        const openAccessSettings = (sectionIndex, materialIndex) => {
            currentAccessSectionIndex.value = { section: sectionIndex, material: materialIndex };
            const material = taskSections.value[sectionIndex].materials[materialIndex];

            if (material.access_users === 'all') {
                selectAllUsers.value = true;
                selectedUsers.value = [...users.value.map(user => user.id)];
            } else {
                selectAllUsers.value = false;
                if (material.access_users) {
                    selectedUsers.value = material.access_users.split(',').map(id => parseInt(id, 10));
                } else {
                    selectedUsers.value = [];
                }
            }

            showAccessSettings.value = true;
        };
        // Закрытие модального окна настройки доступа
        const closeAccessSettings = () => {
            showAccessSettings.value = false;
            currentAccessSectionIndex.value = null;
        };
        // Переключение выбора всех пользователей
        const toggleSelectAllUsers = () => {
            if (selectAllUsers.value) {
                selectedUsers.value = users.value.map(user => user.id);
            } else {
                selectedUsers.value = [];
            }
        };
        // Сохранение настроек доступа
        const saveAccessSettings = () => {
            const { section, material } = currentAccessSectionIndex.value;
            if (section !== null && material !== null) {
                if (selectAllUsers.value) {
                    taskSections.value[section].materials[material].access_users = 'all';
                } else {
                    taskSections.value[section].materials[material].access_users = selectedUsers.value.join(',');
                }
                showAccessSettings.value = false;
                currentAccessSectionIndex.value = null;
            }
        };

        onMounted(() => {
            fetchGroupData();
            userStore.checkAuth();
            myUser.value = userStore.user;
        });

        // Открытие компонента TaskDetail
        const openTaskDetail = async (sectionTaskIndex, materialTaskIndex) => {
            currentSectionName.value = taskSections.value[sectionTaskIndex].sectionName
            currentTaskName.value = taskSections.value[sectionTaskIndex].materials[materialTaskIndex].name
            let response = await axios.get(`/group/${currentGroupId.value}/${currentSectionName.value}/${currentTaskName.value}/users`);
            currentUsers.value = response.data.usersData
            showTaskDetail.value = true;
        }

        const closeTaskDetail = async () => {
            showTaskDetail.value = false;
        }

        return {
            addTaskForm,
            addMaterialSectionForm,
            addTaskSectionForm,
            addEventForm,
            addUserForm,
            addMaterialFileHandler,
            showMaterialSection,
            showTaskSection,
            showMessengerSection,
            showUsersSection,
            showEventsSection,
            saveChanges,
            addMaterialHandler,
            addMaterialSectionHandler,
            addTaskSectionHandler,
            materialSections,
            materialInputs,
            currentGroupId,
            users,
            usersCount,
            open_homePage,
            editRole,
            deleteUser,
            closeAddUserForm,
            showEditUserRoleForm,
            closeEditRoleUserForm,
            currentUser,
            myUser,
            taskSections,
            addTaskHandler,
            addTaskFileHandler,
            taskInputs,
            openAccessSettings,
            closeAccessSettings,
            toggleSelectAllUsers,
            saveAccessSettings,
            showAccessSettings,
            selectAllUsers,
            selectedUsers,
            formatDate,
            toggleDeadline,
            saveDeadline,
            cancelDeadline,
            showTaskDetail,
            currentSectionName,
            currentTaskName,
            currentUsers,
            openTaskDetail,
            closeTaskDetail
        };
    }
};
</script>
