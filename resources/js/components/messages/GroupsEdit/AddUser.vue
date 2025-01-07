<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-60 h-full">
        <div class="flex-col p-4 bg-white rounded-xl min-w-min relative">
            <header class="flex justify-center mx-4">
                <h2 class="font-semibold">
                    Добавление пользователей
                </h2>
                <button type='button'
                        @click="$emit('close')"
                        class='absolute top-2 right-3'>
                    &#x2715;
                </button>
            </header>
            <div class="flex flex-col m-4">
                <label class="relative flex rounded-md p-1 items-center w-full h-full border-black cursor-pointer">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>

                    <input type="text"
                           class="block w-full rounded-md ps-10 p-1 bg-neutral-300 focus:ring-2 focus:ring-cyan-500 focus:outline-none focus:border-cyan-500 focus:rounded-md"
                           placeholder="Иван Иванов, id"
                    />
                </label>
                <ul class="my-3">
                    <li v-for="(user, index) in users" :key="index" class="my-2">
                        <div class="p-2 bg-neutral-200 rounded-xl inline-flex items-center w-full justify-between">
                            <div class="flex items-center mr-3 justify-between min-w-min w-full">
                                <div>
                                    <img v-if="user.image_url" :src="user.image_url" alt="User Image"
                                         class="rounded-full w-12 h-12 object-cover" />
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                         class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-1 rounded-full">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                                <div class="flex justify-between items-center w-full">
                                    <div class="ml-2 p-2 hover:cursor-pointer hover:shadow-inner hover:shadow-neutral-500 hover:rounded-2xl hover:scale-95 transition-transform duration-200 ease-in-out" @click="open_homePage(user, index)">
                                        <div>
                                            {{ user.login || (user.first_name + ' ' + user.second_name) }}
                                        </div>
                                    </div>
                                    <div title="Добавить пользователя в группу">
                                        <button class="flex items-center"
                                            @click="addUserInGroup(user.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                 class="size-7 text-cyan-900 group-hover:text-green-700 hover:scale-95 transition-transform">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, ref} from "vue";
import { useRouter } from "vue-router";

    export default {
        name: 'AddUser',
        props: ['groupId'],
        setup(props, {emit}) {
            // Пользователи которых можно добавить
            const users = ref({})
            const groupId = ref(props.groupId || localStorage.getItem('groupId'));
            const router = useRouter()
            const preferredUserData = ref({})
            onMounted(() => {
                fetchGetFriends()
            });

            // Отображение доступных для добавления пользователей-друзей
            const fetchGetFriends = async() => {
                try {
                    let response = await axios.get(`/group/${groupId.value}/getPreferredFriends`)
                    users.value = response.data.preferredFriendsData
                } catch (e) {
                    if (e.response) {
                        console.warn(e.response.data)
                    }
                    else {
                        console.warn(e.message)
                    }
                }
            }

            // Добавление пользователя в группу
            const addUserInGroup = async (userId) => {
                try {
                    preferredUserData.value = {
                        preferredUserId: userId,
                    }
                    await axios.post(`/groups/${groupId.value}/addPreferredFriends`, preferredUserData.value)
                    await fetchGetFriends()
                } catch (e) {
                    if (e.response) {
                        console.warn(e.response.data)
                    } else {
                        console.warn(e.message)
                    }
                }
            }

            // Переход на страницу пользователя в меню добавления
            const open_homePage = async (userId) => {
                //router.push({ name: 'FriendshipProfile', params: {userId: userId.id} });
                router.push('/profile/' + userId.id);
            }

            return {
                users,
                open_homePage,
                addUserInGroup
            }
        }
    }
</script>
