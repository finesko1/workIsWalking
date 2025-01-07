<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 h-full">
        <div class="flex-col bg-white w-2/3 rounded-lg relative h-96 overflow-y-auto relative">
            <header class="flex justify-center sticky top-0 bg-neutral-200 p-1 items-center rounded-md mb-2">
                <button @click="$emit('close')" class="absolute top-2 right-3 text-gray-500 hover:text-gray-800">
                    &#x2715;
                </button>
                <h2 class="text-xl font-semibold mb-1 text-center">Редактирование группы</h2>
            </header>
            <div class="edit-content-body flex-col space-y-4 w-full flex p-4">
<!--                Создание материалов группы-->
                <div class="edit-material-body flex-col gap-1">
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
                    <div class="flex-col ml-6" v-if="showMaterialSection">
                        <ul class="edit-material-list">
                            <li v-for="(section, sectionIndex) in materialSections" :key="sectionIndex"
                                :class="['',
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
                                <ul class="ml-6 flex-col space-y-1 items-center'">
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
                                @click='addSectionForm = true'
                                v-if="showMaterialSection"
                                class="text-sm font-normal border border-2 border-cyan-700 rounded-md p-1 m-1 bg-cyan-500 hover:scale-95 transition-transform ease-in-out">
                            Создать новый раздел
                        </button>
                    </div>
                </div>
<!--                Создание заданий в группе-->
                <div class="edit-tasks-body">
                    <div class="flex group justify-between edit-tasks-header-content-body items-center"
                         @click="showTaskSection = !showTaskSection">
                        <p class='group-hover:text-green-700 group-hover:scale-90 transition-transform duration-200 ease-in-out select-none'>
                            Задания
                        </p>
                        <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.0" stroke="currentColor"
                                 class="flex size-7 text-cyan-900 group-hover:text-green-700 hover:scale-95 transition-transform group">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
<!--                    Список разделов-->
                    <ul class="section-list" v-if="showTaskSection">
                        <li class=''>
                            <div class="grid grid-cols-6 gap-1 edit-tasks-header-2-content-body items-center justify-between text-center ml-6">
                                <!-- Оглавление раздела -->
                                <div class="col-span-2 italic font-semibold">
                                    Лабораторные работы
                                </div>
                                <div class="min-w-min">
                                    Пользователям
                                </div>
                                <div class="min-w-min">
                                    Срок выполнения
                                </div>
                                <div class="col-span-2 text-start">
                                    Материалы
                                </div>
                            </div>
                            <!-- Содержание раздела -->
                            <ul class="section-content ml-12">
                                <li>
                                    <div class="grid grid-cols-6 edit-tasks-header-2-content-body flex gap-1 items-center justify-between text-center hover:font-semibold">
                                        <div class='col-span-2'>
                                            Лабораторная работа 1
                                        </div>
                                        <div class="hover:bg-gray-100 hover:rounded-md">
                                            Всем...
                                        </div>
                                        <div class="hover:bg-gray-100 hover:rounded-md">
                                            Без срока...
                                        </div>
<!--                                        + материалы -->
                                        <div class="flex items-center ml-6">
                                            <button type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                     class="hover:text-green-600 flex size-6 group-hover:text-green-700 hover:scale-95 transition-transform group">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <!-- Удаление раздела -->
                                        <div class="flex justify-end">
                                            <button type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                     class="size-6 hover:text-red-700">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class='flex justify-center'>
                                <button type="button"
                                        @click='addTaskForm = true'
                                        class="text-sm font-normal border border-2 border-cyan-700 rounded-md p-1 m-1 bg-cyan-500 hover:scale-95 transition-transform ease-in-out">
                                    Добавить задание в раздел
                                </button>
                            </div>
                        </li>
                    </ul>
                    <div class="flex justify-center">
                        <button type="button"
                                @click='addSectionForm = true'
                                v-if="showTaskSection"
                                class="text-sm font-normal border border-2 border-cyan-700 rounded-md p-1 m-1 bg-cyan-500 hover:scale-95 transition-transform ease-in-out">
                            Создать новый раздел
                        </button>
                    </div>
                </div>
<!--                Создание мероприятий-->
                <div class="edit-events-body">
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
                <div class="edit-messenger-body">
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
                            Пользователи
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
                            <div class="col-span-2 italic font-semibold">
                                Роль
                            </div>
                        </div>
                        <ul>
                            <li class="grid grid-cols-3 justify-between hover:font-semibold">
                                <div class="">
                                    Иван иваныч
                                </div>
                                <div class="">
                                    Руководитель
                                </div>
                                <div class="flex justify-end gap-1">
                                    <button type="button"
                                        class="hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="size-6 hover:text-red-700">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                            <li class="grid grid-cols-3 justify-between hover:font-semibold">
                                <div class="">
                                    Петр иваныч
                                </div>
                                <div class="">
                                    Помощник
                                </div>
                                <div class="flex justify-end gap-1">
                                    <button type="button"
                                            class="hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="size-6 hover:text-red-700">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                            <li class="grid grid-cols-3 justify-between hover:font-semibold">
                                <div class="">
                                    Евгений иваныч
                                </div>
                                <div class="">
                                    Участник
                                </div>
                                <div class="flex justify-end gap-1">
                                    <button type="button"
                                            class="hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="size-6 hover:text-red-700">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
<!--            Сохранение изменений-->
            <div class="flex mt-8 justify-center p-1 text-md border-2 border-cyan-800 bg-cyan-600 rounded-md
                    hover:scale-95 transition-transform duration-200 m-4 items-center">
                <div>
                    <button type="button"
                            @click='saveChanges'
                            class="">
                        Сохранить изменения
                    </button>
                </div>
            </div>
            <AddTask v-if='addTaskForm' @close='addTaskForm = false'></AddTask>
            <AddSection v-if='addSectionForm' @getMaterialSectionName="addMaterialSectionHandler" @close='addSectionForm = false'></AddSection>
            <AddEvent v-if='addEventForm' @close='addEventForm = false'></AddEvent>
            <AddUser v-if='addUserForm' :groupid="currentGroupId" @close='addUserForm = false'></AddUser>
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

    export default {
        name: 'GroupsEdit',
        components: {
            AddTask,
            AddSection,
            AddEvent,
            AddUser
        },
        props: ['groupId'],
        setup(props, {emit}) {
            const currentGroupId = ref(props.groupId || localStorage.getItem('groupId'));
            // Ref-объекты для добавления файлов
            const materialInputs = ref([]);
            // Материалы группы
            const materialSections = ref([]);
            // Выпадающие меню добавления разделов
            const addTaskForm = ref(false);
            const addSectionForm = ref(false);
            const addEventForm = ref(false);
            const addUserForm = ref(false);
            // Видимость секций
            const showMaterialSection = ref(false);
            const showTaskSection = ref(false);
            const showMessengerSection = ref(false);
            const showUsersSection = ref(false);
            const showEventsSection = ref(false);


            // Вызов меню выбора файлов
            const addMaterialHandler = (sectionIndex) => {
                if (materialInputs.value[sectionIndex]) {
                    materialInputs.value[sectionIndex].click();
                }
            }
            // Обработка выбранных файлов
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

            // Добавление раздела
            const addMaterialSectionHandler = async (materialSectionName) => {
                materialSections.value.push({
                    sectionName: materialSectionName,
                    materials: [],
                    isNew: true,
                    isDelete: false
                })
                addSectionForm.value = false
            }
            // Сохранение изменений в группе
            const saveChanges = async () => {
                try {
                    const formData = new FormData();
                    let hasMaterials = false;
                    materialSections.value.forEach((section, sectionIndex) => {
                        // Проверка наличия материалов в разделе
                        if (section.materials && section.materials.length > 0) {
                            hasMaterials = true;
                            formData.append(`materialSections[${sectionIndex}][sectionName]`, section.sectionName);
                            formData.append(`materialSections[${sectionIndex}][isNew]`, section.isNew ? 1 : 0);
                            formData.append(`materialSections[${sectionIndex}][isDelete]`, section.isDelete ? 1 : 0);

                            section.materials.forEach((material, materialIndex) => {
                                formData.append(`materialSections[${sectionIndex}][materials][${materialIndex}][materialName]`, material.name);
                                formData.append(`materialSections[${sectionIndex}][materials][${materialIndex}][isNew]`, material.isNew ? 1 : 0);
                                formData.append(`materialSections[${sectionIndex}][materials][${materialIndex}][isDelete]`, material.isDelete ? 1 : 0);

                                // добавление файла
                                if (material.file instanceof File) {
                                    formData.append(`materialSections[${sectionIndex}][materials][${materialIndex}][file]`, material.file);
                                }
                            });
                        } else {
                            // Пропуск раздела, если нет материалов
                            console.warn(`Раздел "${section.sectionName}" не содержит материалов и будет пропущен.`);
                        }
                    });

                    // Проверка, есть ли данные для отправки
                    if (hasMaterials) {
                        const response = await axios.post(`/group/${currentGroupId.value}/save`, formData);
                        showNotification(response.data.message, 1, 1200);
                        emit('close')
                    } else {
                        showNotification('Нет материалов для отправки.', 0, 1200);
                    }
                } catch(e) {
                    if (e.response) {
                        console.log(e.response.data)
                    } else {
                        console.log(e.message)
                    }
                }
            }

            // Получение данных при редактировании группы
            const fetchGroupData = async() => {
                try {
                    const response = await axios.get(`/group/${currentGroupId.value}/getData`)
                    materialSections.value = response.data.materialSections || [];
                } catch (e) {
                    if (e.response) {
                        console.log(e.response.data)
                    } else {
                        console.log(e.message)
                    }
                }
            }


            onMounted(() => {
                fetchGroupData();
            });


            return {
                addTaskForm,
                addSectionForm,
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
                materialSections,
                materialInputs,
                currentGroupId
            };
        }
    };
</script>
