<template>
    <div class='flex justify-center w-full'>
        <div v-if="blocked.length === 0">
            <p>Нет заблокированных пользователей</p>
        </div>
        <div v-else class="">
            <ul>
                <li v-for="block in blocked" :key="blocked.id" class="">
                    <div class="p-2 bg-neutral-400 rounded-xl inline-flex items-center w-full justify-between">
                        <div class="flex items-center mr-3">
                            <img v-if="block.image_url" :src="block.image_url" alt="User Image"
                                 class="rounded-full w-12 h-12 object-cover" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-1 rounded-full">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <div class="ml-2">
                                {{ block.login || (block.first_name + ' ' + block.second_name) }}
                            </div>
                        </div>


                        <div class="">
                            <button @click="unblockUser(block.id)" class="flex items-center rounded-full font-medium  text-black p-2 hover:bg-cyan-700 hover:scale-95 hover:text-green-400 transition-transform duration-300 ease-in-out w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Разблокировать
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { onMounted, ref } from "vue";

export default {
    name: 'Blocked',
    setup() {
        const blocked = ref([]);

        onMounted(async () => {
            await loadBlocked();
        });


        const loadBlocked = async () => {
            try {
                const response = await axios.get('/friendship/blocked');
                blocked.value = response.data.blockedData || [];
            } catch (e) {
                if (e.response) {
                    console.log(e.response.error);
                } else {
                    console.log(e.message);
                }
            }
        }


        const unblockUser = async (userId) => {
            try {
                await axios.delete('/friendship/friends/block/' + userId);
                await loadBlocked();
            } catch (e) {
                console.log(e.message);
            }
        }


        return {
            blocked,
            unblockUser
        }
    }
}
</script>
