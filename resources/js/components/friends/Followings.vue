<template>
    <div class='flex justify-center w-full'>
        <div v-if="followings.length === 0">
            <p>Нет подписок</p>
        </div>
        <div v-else class="">
            <ul>
                <li v-for="following in followings" :key="following.id" class="">
                    <div v-if="following.login" class="p-2 bg-gray-400 inline-flex items-center w-full">
                        <div class="flex">
                            <img v-if="following.image_url" :src="following.image_url" alt="User Image" class="rounded-full w-12 h-12 object-cover" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-2 rounded-full">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="p-2">
                            {{ following.login }}
                        </div>
                    </div>
                    <div v-else class="p-2 bg-gray-400 inline-flex items-center w-full">
                        <div class="flex">
                            <img v-if="following.image_url" :src="following.image_url" alt="User Image" class="rounded-full w-12 h-12 object-cover" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-12 h-12 text-white border border-cyan-900 bg-cyan-800 p-2 rounded-full">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="p-2">
                            {{ following.first_name }} {{ following.second_name }}
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
    name: 'Followings',
    setup() {
        const followings = ref([]);

        onMounted(async () => {
            await loadFollowers();
        });


        const loadFollowers = async () => {
            try {
                const response = await axios.get('/friendship/followings');
                followings.value = response.data.followingsData || [];
            } catch (e) {
                if (e.response) {
                    console.log(e.response.error);
                } else {
                    console.log(e.message);
                }
            }
        }


        return {
            followings,
        }
    }
}
</script>
