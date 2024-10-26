<template>
    <div class='flex justify-center w-full'>
        <div v-if="users.length === 0">
            <p>Ошибка загрузки пользователей</p>
        </div>
        <div v-else class="">
            <ul>
                <li v-for="(user, index) in users" :key="user.id" class="mb-1">
                    <div class="p-2 bg-gray-400 inline-flex items-center w-full justify-between">
                        <div class="flex items-center mr-2">
                            <img v-if="user.image_url" :src="user.image_url" alt="User Image" class="rounded-full w-12 h-12 object-cover" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-2 rounded-full">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <div class="ml-2">
                                {{ user.login || (user.first_name + ' ' + user.second_name) }}
                            </div>
                        </div>

                        <div ref="checkDropdown" class="relative dropdown">
                            <div class="flex items-center border-2 rounded-xl border-cyan-800 bg-cyan-600 text-white p-1">
                                <button class="text-white" disabled>
                                    <span v-if="user.status === 'accepted'">В друзьях</span>
                                    <span v-else-if="user.status === null">Отправить заявку</span>
                                    <span v-else-if="user.status === 'following'">Заявка отправлена</span>
                                    <span v-else-if="user.status === 'pending'">Ожидает ответ</span>
                                    <span v-else-if="user.status === 'blockIt'">Заблокирован</span>
                                    <span v-else-if="user.status === 'blockMe'">Вы в черном списке</span>
                                </button>
                                <button @click="toggleDropdown(index)">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Действия в выпадающем меню -->
                            <div v-if="user.showOptions" class="absolute bg-white shadow-md rounded p-2 mt-1 z-10">
                                <button v-if="user.status === null" @click="addFollowing(user.id)" class="flex items-center rounded-full text-green-400 p-2 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Добавить в друзья
                                </button>
                                <button @click="blockUser(user.id)" class="flex items-center rounded-full text-red-400 p-2 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6m6 6V6" />
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
</template>

<script>
import {onBeforeUnmount, onMounted, ref} from "vue";

export default {
    name: 'SearchFriends',
    setup() {
        const users = ref([]);
        const checkDropdown = ref();


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
                console.log(users.value);
            } catch (e) {
                if (e.response) {
                    console.log(e.response.error);
                } else {
                    console.log(e.message);
                }
            }
        }


        const toggleDropdown = (index) => {
            users.value.forEach((user, i) => {
                user.showOptions = i === index ? !user.showOptions : false;
            });
        };

        const handleClickOutside = (event) => {
            if (!event.target.closest('.relative.dropdown')) {
                users.value.forEach(user => user.showOptions = false);
            }
        };


        const addFollowing = async (friendId) => {
            try {
                await axios.post(`/friendship/friends/following/${friendId}`);
            } catch(e) {
                console.log(e.message);
            }
        }

        return {
            users,
            addFollowing,
            checkDropdown,
            toggleDropdown
        }
    }
}
</script>
