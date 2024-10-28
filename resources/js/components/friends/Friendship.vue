<template>
    <div class="flex flex-col items-center justify-start m-2">
        <div class="flex flex-col shadow-lg bg-neutral-300 p-6 rounded-lg w-full max-w-[500px] min-h-full">
            <div class="flex justify-center p-4 text-lg font-semibold">
                {{ headForm }}
            </div>
            <div>
                <ul class="flex items-center justify-center gap-2 p-2 text-white">
                    <!-- Друзья -->
                    <li class="p-2 text-black text-lg no-underline hover:underline underline-offset-4 decoration-dashed rounded-xl">
                        <router-link to='/friendship/friends'>
                            Друзья
                        </router-link>
                    </li>
                    <li class="relative p-2 text-black text-lg no-underline hover:underline underline-offset-4 decoration-dashed rounded-xl">
                        <div class="flex items-center justify-center gap-1 w-40">
                            <label @click="navigateTo(currentLinkPath)" class="hover:cursor-pointer">
                                {{ currentLink }}
                            </label>
                            <button @click.stop="dropdownState = !dropdownState" class="ml-1 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>

                        <div v-if="dropdownState" class="absolute left-0 z-10 w-full mt-1 bg-gray-400 rounded-lg shadow-lg">
                            <div
                                v-for="link in links"
                                :key="link.name"
                                @click="selectLink(link)"
                                class="block px-4 py-2 hover:bg-gray-500 rounded cursor-pointer"
                            >
                                {{ link.name }}
                            </div>
                        </div>
                    </li>
                    <li class="p-2 text-black text-lg no-underline hover:underline underline-offset-4 decoration-dashed rounded-xl">
                        <router-link to='/friendship/search'>
                            Поиск
                        </router-link>
                    </li>
                </ul>
            </div>
            <div class="w-full p-4">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";

export default {
    name: 'Friendship',
    setup() {
        const headForm = ref('Список друзей');
        const currentLink = ref('Подписчики');
        const currentLinkPath = ref('/friendship/followers'); // начальный путь
        const dropdownState = ref(false);
        const route = useRoute();
        const router = useRouter();

        const links = [
            { name: 'Подписчики', path: '/friendship/followers' },
            { name: 'Заявки', path: '/friendship/pendings' },
            { name: 'Подписки', path: '/friendship/followings' },
            { name: 'Заблокированные', path: '/friendship/blocked' }
        ];

        const updateHeadForm = () => {
            if (route.path === '/friendship/friends') {
                headForm.value = 'Список друзей';;
            } else if (route.path === '/friendship/search') {
                headForm.value = 'Поиск друзей';
            } else if (route.path === '/friendship/pendings') {
                headForm.value = 'Заявки';
            } else if (route.path === '/friendship/followers') {
                headForm.value = 'Список подписчиков';
            } else if (route.path === '/friendship/followings') {
                headForm.value = 'Список отправленных заявок';
            } else if (route.path === '/friendship/blocked') {
                headForm.value = 'Список заблокированных пользователей';
            } else {
                headForm.value = 'Люди';
            }
        };

        watch(() => route.path, () => {
            updateHeadForm();
        });

        const navigateTo = (path) => {
            router.push(path);
        }
        const selectLink = (link) => {
            currentLink.value = link.name;
            currentLinkPath.value = link.path;
            dropdownState.value = false;
            navigateTo(link.path);
        };

        return {
            headForm,
            currentLink,
            currentLinkPath,
            dropdownState,
            links,
            selectLink,
            navigateTo
        }
    }
}
</script>

<style scoped>
/* Дополнительные стили, если нужно */
</style>
