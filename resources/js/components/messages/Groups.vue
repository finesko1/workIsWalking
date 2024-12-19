<template>
    <div v-if="isShowGroups"
         class="relative group-body flex-col p-2 mx-auto w-full h-full max-w-lg">
        <!-- Перекрытие border -->
        <div class="sticky top-0 z-10 w-full h-2 bg-neutral-300"></div>
        <nav class="sticky top-0 z-20 flex justify-center w-full border border-1 rounded-t-xl border-black bg-neutral-300 shadow-md">
            <div class="relative flex rounded-md p-1 items-center w-full h-full border-black">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>

                <input type="text"
                       class="block w-full ps-10 p-1 bg-neutral-300 focus:ring-2 focus:ring-cyan-500 focus:outline-none focus:border-cyan-500 focus:rounded-md"
                       placeholder="Поиск"
                />
                <button type="button"
                        class="absolute inset-y-0 end-2.5"
                        @click="openCreateGroup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </button>
            </div>
        </nav>

        <div class="group-content flex-col p-2 space-y-1 w-full justify-center border border-black border-1 border-t-0 rounded-b-xl max-h-full overflow-y-auto">
            <GroupsView @close="isShowGroups = false"/>
<!--            Логика для активации строки поиска-->
        </div>

        <!-- Модальное окно создания группы -->
        <GroupsCreate v-if="isCreateGroupVisible" @close="closeCreateGroup" />
    </div>
    <div v-else>
        <GroupView @close="isShowGroups = true"></GroupView>
    </div>
</template>


<script>
import {computed, onMounted, ref, watch} from "vue";
import { useRoute, useRouter } from "vue-router";
import GroupsView from "@/components/messages/GroupsView/GroupsView.vue";
import GroupsCreate from "@/components/messages/GroupsCreate.vue";
import GroupView from "@/components/messages/GroupsView/GroupView.vue";
import {showNotification} from "@/notifications.js";

export default {
    name: 'Groups',
    components: {
        GroupsView,
        GroupView,
        GroupsCreate,
    },
    setup() {
        const router = useRouter();
        const route = useRoute();
        const isShowGroups = ref(false);

        // Определяем видимость модального окна создания группы
        const isCreateGroupVisible = computed(() => route.path === '/groups/create');

        onMounted(async () => {
            if (route.path === '/groups' || route.path === '/groups/edit' || route.path === '/groups/create') {
                isShowGroups.value = true;
            } else {
                isShowGroups.value = false;
            }
        });

        watch(() => route.path, (newPath, oldPath) => {
            if (newPath === '/groups' || newPath === '/groups/edit' || newPath === '/groups/create') {
                isShowGroups.value = true;
                console.log(isShowGroups.value)
            } else {
                isShowGroups.value = false;
            }
        });


        // Функции для открытия и закрытия модального окна
        const openCreateGroup = () => {
            router.push('/groups/create');
        };

        const closeCreateGroup = () => {
            router.push('/groups');
            //await new Promise(resolve => setTimeout(resolve,1));
            //window.location.reload();
        };

        return {
            isCreateGroupVisible,
            openCreateGroup,
            closeCreateGroup,
            isShowGroups
        };
    }
};
</script>
