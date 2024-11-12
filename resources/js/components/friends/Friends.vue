<template>
    <div class='flex justify-center w-full'>
        <div v-if="isDataLoaded">
            <div v-if="friends.length === 0">
                <p>Нет друзей</p>
            </div>
            <div v-else class="">
                <ul>
                    <li v-for="(friend, index) in friends" :key="friends.id" class="mb-1">
                        <div class="p-2 bg-neutral-400 rounded-xl inline-flex items-center w-full justify-between">
                            <div class="flex items-center mr-3">
                                <img v-if="friend.image_url" :src="friend.image_url" alt="User Image"
                                     class="rounded-full w-12 h-12 object-cover" />
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                     class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-1 rounded-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <div class="ml-2">
                                    {{ friend.login || (friend.first_name + ' ' + friend.second_name) }}
                                </div>
                            </div>

                            <div ref="checkDropdown" class="relative dropdown flex-inline">
                                <div class="flex items-center shadow-lg rounded-xl bg-cyan-600 text-white p-1 mainButton w-48 justify-between">
                                    <div class="text-white" @click="toggleDropdown(index)">
                                        <div class="hover:scale-95 hover:cursor-pointer transition-transform duration-200 ease-in-out">
                                            Действия
                                        </div>
                                    </div>
                                    <button id="dropdownButton" type='button' @click="toggleDropdown(index)" class="hover:scale-90 transition-transform">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div v-if="friend.showOptions" class="absolute bg-cyan-600 shadow-lg rounded-b-xl p-2 z-10 w-full">
                                    <button @click="addFollower(friend.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
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
                                    <button @click="blockUser(friend.id, index)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-cyan-700 hover:scale-95 transition-transform duration-300 ease-in-out w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                        </svg>
                                        Заблокировать
                                    </button>
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
import { showAlertWindow  } from "@/alertWindow.js";

export default {
    name: 'Friends',
    setup() {
        const friends = ref([]);
        const isDataLoaded = ref(false);

        onMounted(async () => {
            await loadFriendships();
            document.addEventListener('click', handleClickOutside);
        });

        onBeforeUnmount(() => {
            document.removeEventListener('click', handleClickOutside);
        })


        const loadFriendships = async () => {
            try {
                const response = await axios.get('/friendship/friends');
                friends.value = response.data.friendsData || [];
                friends.value.forEach(user => {
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


        const addFollower = async (userId, index) => {
            try {
                let userName = friends.value[index].login || friends.value[index].first_name + ' ' + friends.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Удалить из друзей пользователя " + userName + '?');
                if (resultAlert === true) {
                    await axios.delete('/friendship/friends/' + userId);
                    await loadFriendships();
                }
                hideMainButton();
            } catch(e) {
                console.log(e.message);
            }
        }

        const blockUser = async (userId, index) => {
            try {
                let userName = friends.value[index].login || friends.value[index].first_name + ' ' + friends.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Заблокировать пользователя " + userName + '?');
                if (resultAlert === true) {
                    await axios.post('/friendship/friends/block/' + userId);
                    await loadFriendships();
                }
                hideMainButton();
            } catch (e) {
                console.log(e.message);
            }
        }


        const toggleDropdown = (index) => {
            let activeButton = document.querySelector('.mainButton.rounded-t-xl');
            if (activeButton) {
                activeButton.classList.remove('rounded-t-xl');
                activeButton.classList.add('rounded-xl');
            }

            let dropdownButtons = document.getElementsByClassName('mainButton');

            friends.value.forEach((user, i) => {
                user.showOptions = i === index ? !user.showOptions : false;
            });

            activeButton = dropdownButtons[index];

            if (friends.value[index].showOptions) {
                activeButton.classList.remove('rounded-xl');
                activeButton.classList.add('rounded-t-xl');
            } else {
                activeButton.classList.remove('rounded-t-xl');
                activeButton.classList.add('rounded-xl');
            }
        }

        const handleClickOutside = (event) => {
            if (!event.target.closest('.relative.dropdown')) {
                friends.value.forEach(user => user.showOptions = false);

                const activeButton = document.querySelector('.mainButton.rounded-t-xl');
                if (activeButton) {
                    activeButton.classList.remove('rounded-t-xl');
                    activeButton.classList.add('rounded-xl');
                }
            }
        }

        const hideMainButton = () => {
            const activeButton = document.querySelector('.mainButton.rounded-t-xl');
            if (activeButton) {
                activeButton.classList.remove('rounded-t-xl');
                activeButton.classList.add('rounded-xl');
            }
        }

        return {
            friends,
            addFollower,
            blockUser,
            toggleDropdown,
            isDataLoaded
        }
    }
}
</script>
