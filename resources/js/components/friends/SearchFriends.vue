<template>
    <div class='flex justify-center w-full'>
        <div v-if="isDataLoaded">
            <div v-if="users.length === 0">
                <p>Ошибка загрузки пользователей</p>
            </div>
            <div v-else class="p-1">
                <ul>
                    <li v-for="(user, index) in users" :key="user.id" class="mb-1">
                        <div class="p-2 bg-neutral-400 rounded-xl inline-flex items-center w-full justify-between">
                            <div class="flex items-center mr-3">
                                <img v-if="user.image_url" :src="user.image_url" alt="User Image"
                                     class="rounded-full w-12 h-12 object-cover" />
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                     class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-1 rounded-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <div class="ml-2 p-2 hover:cursor-pointer hover:shadow-inner hover:shadow-neutral-500 hover:rounded-2xl hover:scale-95 transition-transform duration-200 ease-in-out" @click="open_homePage(user, index)">
                                    {{ user.login || (user.first_name + ' ' + user.second_name) }}
                                </div>
                            </div>

                            <div ref="checkDropdown" class="relative dropdown">
                                <div class="flex items-center shadow-lg rounded-xl bg-cyan-600 text-white p-1 mainButton w-48 justify-between">
                                    <div class="text-white hover:scale-95 hover:cursor-pointer transition-transform duration-300 ease-in-out">
                                        <div v-if="user.status === 'accepted'" @click="toggleDropdown(index)">В друзьях</div>
                                        <div v-else-if="user.status === null" @click="addFollowing(user.id, index)">Отправить заявку</div>
                                        <div v-else-if="user.status === 'follower'" @click="toggleDropdown(index)">Подписан на вас</div>
                                        <div v-else-if="user.status === 'following'" @click="toggleDropdown(index)">Заявка отправлена</div>
                                        <div v-else-if="user.status === 'pending'" @click="toggleDropdown(index)">Ожидает ответ</div>
                                        <div v-else-if="user.status === 'blockIt'" @click="toggleDropdown(index)">Заблокирован</div>
                                        <div v-else-if="user.status === 'blockMe'" @click="toggleDropdown(index)" class="">Вы в черном списке</div>
                                        <div v-else-if="user.status === 'blocked'" @click="toggleDropdown(index)">Заблокирован</div>
                                    </div>
                                    <button type='button' @click="toggleDropdown(index)" class="hover:scale-90 transition-transform">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>


                                <div v-if="user.showOptions" class="absolute bg-cyan-600 shadow-lg rounded-b-xl p-2 z-10 w-full">
<!--                                    В друзьях-->
                                    <div v-if="user.status === 'accepted'" class="w-full">
                                        <button @click="addFollower(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                            </svg>
                                            Удалить из друзей
                                        </button>
                                        <button @click="" class="flex items-center rounded-full text-white p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>
                                            Написать сообщение
                                        </button>
                                        <button @click="blockUser(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Заблокировать
                                        </button>
                                    </div>

<!--                                    Не был добавлен-->
                                    <div  v-else-if="user.status === null" class="w-full">
                                        <button @click="" class="flex items-center rounded-full text-white p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>
                                            Написать сообщение
                                        </button>
                                        <button @click="blockUser(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Заблокировать
                                        </button>
                                    </div>

<!--                                    Подписан на вас-->
                                    <div v-else-if="user.status === 'follower'" class="w-full">
                                        <button @click="addFriend(user.id, index)" class="flex items-center rounded-full text-green-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6m6 6V6" />
                                            </svg>
                                            Принять в друзья
                                        </button>
                                        <button @click="" class="flex items-center rounded-full text-white p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>
                                            Написать сообщение
                                        </button>
                                        <button @click="blockUser(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Заблокировать
                                        </button>
                                    </div>

<!--                                    Вы подписаны на него-->
                                    <div v-else-if="user.status === 'following'" class="w-full">
                                        <button @click="cancelFollowing(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                            </svg>
                                            Отменить заявку
                                        </button>
                                        <button @click="" class="flex items-center rounded-full text-white p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>
                                            Написать сообщение
                                        </button>
                                        <button @click="blockUser(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Заблокировать
                                        </button>
                                    </div>

<!--                                    Ожидает ваш ответ-->
                                    <div v-else-if="user.status === 'pending'" class="w-full">
                                        <button @click="addFriend(user.id, index)" class="flex items-center rounded-full text-green-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Принять в друзья
                                        </button>
                                        <button @click="addFollower(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                            </svg>
                                            Оставить в подписчиках
                                        </button>
                                        <button @click="" class="flex items-center rounded-full text-white p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>
                                            Написать сообщение
                                        </button>
                                        <button @click="blockUser(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Заблокировать
                                        </button>
                                    </div>

<!--                                    Вы его заблокировали-->
                                    <div v-else-if="user.status === 'blockIt'" class="w-full">
                                        <button @click="unblockUser(user.id, index)" class="flex items-center rounded-full font-medium  text-black p-2 hover:bg-cyan-700 hover:scale-95 group hover:text-green-400 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-red-600 group-hover:hidden">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-600 hidden group-hover:block">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Разблокировать
                                        </button>
                                    </div>

<!--                                    Он вас заблокировал-->
                                    <div v-else-if="user.status === 'blockMe'" class="w-full">
                                        <button @click="blockUser(user.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Заблокировать
                                        </button>
                                    </div>

<!--                                    Вы заблокировали друг друга-->
                                    <div v-else-if="user.status === 'blocked'" class="w-full">
                                        <button @click="unblockUser(user.id, index)" class="flex items-center rounded-full font-medium  text-black p-2 hover:bg-cyan-700 hover:scale-95 group hover:text-green-400 transition-transform duration-300 ease-in-out w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-red-600 group-hover:hidden">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-600 hidden group-hover:block">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Разблокировать
                                        </button>
                                    </div>

<!--                                    Неизвестная ошибка-->
                                    <div v-else class="text-red-500">
                                        <span>Ошибка сервера</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div v-else>
            <button type="button" class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-cyan-700 hover:bg-cyan-600 transition ease-in-out duration-150 cursor-not-allowed" disabled="">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Загрузка...
            </button>
        </div>
    </div>
</template>

<script>
import {onBeforeUnmount, onMounted, ref} from "vue";
import {showAlertWindow} from "@/alertWindow.js";
import { useRouter } from "vue-router";
export default {
    name: 'SearchFriends',
    setup() {
        const users = ref([]);
        const checkDropdown = ref();
        const isDataLoaded = ref(false);
        const router = useRouter();


        onMounted(async () => {
            await loadSearchFriends();
            document.addEventListener('click', handleClickOutside);
        });

        onBeforeUnmount(() => {
            document.removeEventListener('click', handleClickOutside);
        });


        const loadSearchFriends = async () => {
            try {

                const response = await axios.get('/friendship/search');
                users.value = response.data.usersData || [];
                users.value.forEach(user => {
                    user.showOptions = false;
                });
                await new Promise(resolve => setTimeout(resolve, 200));
                isDataLoaded.value = true;
            } catch (e) {
                if (e.response) {
                    console.log(e.response.error);
                } else {
                    console.log(e.message);
                }
            }
        }

        const toggleDropdown = (index) => {
            let activeButton = document.querySelector('.mainButton.rounded-t-xl');
            if (activeButton) {
                activeButton.classList.remove('rounded-t-xl');
                activeButton.classList.add('rounded-xl');
            }

            let dropdownButtons = document.getElementsByClassName('mainButton');

            users.value.forEach((user, i) => {
                user.showOptions = i === index ? !user.showOptions : false;
            });

            activeButton = dropdownButtons[index];

            if (users.value[index].showOptions) {
                activeButton.classList.remove('rounded-xl');
                activeButton.classList.add('rounded-t-xl');
            } else {
                activeButton.classList.remove('rounded-t-xl');
                activeButton.classList.add('rounded-xl');
            }
        };

        const handleClickOutside = (event) => {
            if (!event.target.closest('.relative.dropdown')) {
                users.value.forEach(user => user.showOptions = false);

                const activeButton = document.querySelector('.mainButton.rounded-t-xl');
                if (activeButton) {
                    activeButton.classList.remove('rounded-t-xl');
                    activeButton.classList.add('rounded-xl');
                }
            }
        };

        const hideMainButton = () => {
            const activeButton = document.querySelector('.mainButton.rounded-t-xl');
            if (activeButton) {
                activeButton.classList.remove('rounded-t-xl');
                activeButton.classList.add('rounded-xl');
            }
        }

        const addFollowing = async (userId, index) => {
            try {
                let userName = users.value[index].login || users.value[index].first_name + ' ' + users.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Отправить заявку пользователю " + userName + '?');
                if (resultAlert === true) {
                    await axios.post('/friendship/friends/following/' + userId);
                    await loadSearchFriends();
                }
                hideMainButton();
            } catch(e) {
                console.log(e.message);
            }
        }

        const addFollower = async (userId, index) => {
            try {
                let userName = users.value[index].login || users.value[index].first_name + ' ' + users.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Отклонить заявку пользователя " + userName + '?');
                if (resultAlert === true) {
                    await axios.delete('/friendship/friends/' + userId);
                    await loadSearchFriends();
                }
                hideMainButton();
            } catch(e) {
                console.log(e.message);
            }
        }

        const cancelFollowing = async (userId, index) => {
            try {
                let userName = users.value[index].login || users.value[index].first_name + ' ' + users.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Отменить заявку пользователю " + userName + '?');
                if (resultAlert === true) {
                    await axios.delete('/friendship/friends/following/' + userId);
                    await loadSearchFriends();
                }
                hideMainButton();
            } catch(e) {
                console.log(e.message);
            }
        }

        const addFriend = async (userId, index) => {
            try {
                let userName = users.value[index].login || users.value[index].first_name + ' ' + users.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Добавить в друзья пользователя " + userName + '?');
                if (resultAlert === true) {
                    await axios.post('/friendship/friends/' + userId);
                    await loadSearchFriends();
                }
                hideMainButton();
            } catch(e) {
                console.log(e.message);
            }
        }

        const blockUser = async (userId, index) => {
            try {
                let userName = users.value[index].login || users.value[index].first_name + ' ' + users.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Заблокировать пользователя " + userName + '?');
                if (resultAlert === true) {
                    await axios.post('/friendship/friends/block/' + userId);
                    await loadSearchFriends();
                }
                hideMainButton();
            } catch (e) {
                console.log(e.message);
            }
        }

        const unblockUser = async (userId, index) => {
            try {
                let userName = users.value[index].login || users.value[index].first_name + ' ' + users.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Разблокировать пользователя " + userName + '?');
                if (resultAlert === true) {
                    await axios.delete('/friendship/friends/block/' + userId);
                    await loadSearchFriends();
                }
                hideMainButton();
            } catch (e) {
                console.log(e.message);
            }
        }

        const open_homePage = async (userId) => {
            //router.push({ name: 'FriendshipProfile', params: {userId: userId.id} });
            router.push('/profile/' + userId.id);
        }
        return {
            users,
            checkDropdown,
            toggleDropdown,
            addFollowing,
            addFollower,
            cancelFollowing,
            addFriend,
            blockUser,
            unblockUser,
            open_homePage,
            isDataLoaded
        }
    }
}
</script>
