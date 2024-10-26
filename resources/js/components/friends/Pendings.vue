<template>
    <div class='flex justify-center w-full'>
        <div v-if="pendings.length === 0">
            <p>Нет подписчиков</p>
        </div>
        <div v-else class="">
            <ul>
                <li v-for="pending in pendings" :key="pending.id" class="">
                    <div v-if="pending.login" class="p-2 bg-gray-400 inline-flex items-center w-full">
                        <div class="flex">
                            <img v-if="pending.image_url" :src="pending.image_url" alt="User Image" class="rounded-full w-12 h-12 object-cover" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-2 rounded-full">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="p-2">
                            {{ pending.login }}
                        </div>
                        <button @click="addFriend(pending.id)" class="rounded-full text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="size-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                        <button @click="addFollower(pending.id)" class="rounded-full text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="size-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                    <div v-else class="p-2 bg-gray-400 inline-flex items-center w-full">
                        <div class="flex">
                            <img v-if="pending.image_url" :src="pending.image_url" alt="User Image" class="rounded-full w-12 h-12 object-cover" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-12 h-12 text-white border border-cyan-900 bg-cyтan-800 p-2 rounded-full">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="p-2">
                            {{ pending.first_name }} {{ pending.second_name }}
                        </div>
                        <button @click="addFriend(pending.id)" class="rounded-full text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="size-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                        <button @click="addFollower(pending.id)" class="rounded-full text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="size-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { onMounted, ref } from "vue";

export default {
    name: 'Pendings',
    setup() {
        const pendings = ref([]);

        onMounted(async () => {
            await loadPendings();
        });


        const loadPendings = async () => {
            try {
                const response = await axios.get('/friendship/pendings');
                pendings.value = response.data.pendingsData || [];
            } catch (e) {
                if (e.response) {
                    console.log(e.response.error);
                } else {
                    console.log(e.message);
                }
            }
        }


        const addFriend = async (friendId) => {
            try {
                await axios.post('/friendship/friends/' + friendId);
                pendings.value = pendings.value.filter(friend => friend.id !== friendId);
            } catch(e) {
                console.log(e.message);
            }
        }

        const addFollower = async (friendId) => {
            try {
                await axios.delete('/friendship/friends/' + friendId);
                pendings.value = pendings.value.filter(friend => friend.id !== friendId);
            } catch(e) {
                console.log(e.message);
            }
        }


        return {
            pendings,
            addFollower,
            addFriend
        }
    }
}
</script>
