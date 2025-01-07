<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20 h-full">
        <div class="flex-col p-4 bg-white rounded-xl min-w-min relative">
            <header class="flex justify-center">
                <h2 class="justify-center mx-10">
                    Участники группы
                </h2>
                <button type='button'
                        @click="$emit('close')"
                        class='absolute top-2 right-3'>
                    &#x2715;
                </button>
            </header>
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
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import {onMounted, ref} from "vue";
import {showNotification} from "@/notifications.js";
import {useRouter } from "vue-router";

export default {
    name: 'GroupMembers',
    props: ['groupId'],
    setup(props) {
        const users = ref({})
        const router = useRouter()

        onMounted(() => {
            fetchGetUsersFromGroup()
        });

        const fetchGetUsersFromGroup = async() => {
            try {
                let response = await axios.get(`/group/${props.groupId}/users`)
                users.value = response.data.usersData
            } catch (e) {
                if (e.response) {
                    showNotification(e.response.error, 0, 1000)
                }
                else {
                    console.log(e.message)
                }
            }
        }

        const open_homePage = async (userId) => {
            //router.push({ name: 'FriendshipProfile', params: {userId: userId.id} });
            router.push('/profile/' + userId.id);
        }

        return {
            users,
            open_homePage
        }
    }
}
</script>
