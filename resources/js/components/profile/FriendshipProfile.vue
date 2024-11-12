<template>
    <div class="flex justify-center p-6">
        <div class="shadow-lg bg-neutral-300 rounded-lg p-6 w-full max-w-md">
            <div v-if="isDataLoaded" class="flex flex-col">
                <div class="flex items-center mb-4">
                    <div class='mr-2'>
                        <img v-if="friendshipUser.image_url" :src="friendshipUser.image_url" alt="User Image"
                             class="rounded-full w-16 h-16 object-cover" />
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                             class="w-16 h-16 text-white border border-cyan-900 bg-cyan-800 p-1 rounded-full">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ friendshipUser.login || (friendshipUser.first_name + ' ' + friendshipUser.second_name) }}
                    </h2>
                </div>
                <div class="ml-4 font-serif">
                    Дополнительная информация (доработать поля)
                </div>
            </div>
            <div v-else class="flex justify-center mt-4">
                <button type="button" class="flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-cyan-700 hover:bg-cyan-600 transition ease-in-out duration-150 cursor-not-allowed" disabled>
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Загрузка...
                </button>
            </div>
        </div>
    </div>
</template>

<script>


import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";

export default {
    name: 'FriendshipProfile',
    props: ['userId'],
    setup(props) {
        const router = useRouter();
        const friendshipUser = ref({});
        const isDataLoaded = ref(false);
        onMounted(async() => {
            await loadFriendshipProfile();
        });

        const loadFriendshipProfile = async() => {
            try {
                const response = await axios.get('/profile/' + props.userId) ;
                friendshipUser.value = response.data.user || [];
                await new Promise(resolve => setTimeout(resolve, 200));
                isDataLoaded.value = true;
            } catch (e) {
                friendshipUser.value = null;
                isDataLoaded.value = false;
            }
        }

        return {
            friendshipUser,
            isDataLoaded
        }
    }
}
</script>
