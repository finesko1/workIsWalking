<template>
    <div class='flex justify-center w-full'>
        <div v-if="isDataLoaded">
            <div v-if="blocked.length === 0">
                <p>Нет заблокированных пользователей</p>
            </div>
            <div v-else class="">
                <ul>
                    <li v-for="(block, index) in blocked" :key="block.id" class="mb-1">
                        <div class="p-2 bg-neutral-400 rounded-xl inline-flex items-center w-full justify-between">
                            <div class="flex items-center mr-3">
                                <img v-if="block.image_url" :src="block.image_url" alt="User Image"
                                     class="rounded-full w-12 h-12 object-cover" />
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                     class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-1 rounded-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <div class="ml-2 p-2 hover:cursor-pointer hover:shadow-inner hover:shadow-neutral-500 hover:rounded-2xl hover:scale-95 transition-transform duration-200 ease-in-out" @click="open_homePage(block)">
                                    {{ block.login || (block.first_name + ' ' + block.second_name) }}
                                </div>
                            </div>


                            <div>
                                <button @click="unblockUser(block.id, index)" class="flex items-center rounded-full font-medium  text-black p-2 hover:bg-cyan-700 hover:scale-95 group hover:text-green-400 transition-transform duration-300 ease-in-out w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-red-600 group-hover:hidden">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-600 hidden group-hover:block">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                    Разблокировать
                                </button>
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
import { onMounted, ref } from "vue";
import {showAlertWindow} from "@/alertWindow.js";
import {useRouter} from "vue-router";

export default {
    name: 'Blocked',
    setup() {
        const blocked = ref([]);
        const isDataLoaded = ref(false);
        const router = useRouter();

        onMounted(async () => {
            await loadBlocked();
        });


        const loadBlocked = async () => {
            try {
                const response = await axios.get('/friendship/blocked');
                blocked.value = response.data.blockedData || [];
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


        const unblockUser = async (userId, index) => {
            try {
                let userName = blocked.value[index].login || blocked.value[index].first_name + ' ' + blocked.value[index].second_name;
                let resultAlert = await showAlertWindow("Подтверждение", "Разблокировать пользователя " + userName + '?');
                if (resultAlert === true) {
                    await axios.delete('/friendship/friends/block/' + userId);
                    await loadBlocked();
                }
            } catch (e) {
                console.log(e.message);
            }
        }

        const open_homePage = async (userId) => {
            router.push('/profile/' + userId.id);
        }


        return {
            blocked,
            unblockUser,
            open_homePage,
            isDataLoaded
        }
    }
}
</script>
