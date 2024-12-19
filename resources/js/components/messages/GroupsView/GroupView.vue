<template class="h-screen">
    <div class="grid grid-cols-3 gap-4 p-4 min-h-full">
        <!-- Левая колонка -->
        <div class="flex justify-start items-start">
            <button
                type="button"
                @click="$router.push('/groups')"
                class="text-gray-500 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                </svg>
            </button>
        </div>

        <!-- Центральная колонка -->
        <div class="flex flex-col items-center">
            <header class="font-semibold text-lg mb-4">
                Группа
            </header>
            <div class="grow w-full">
                <div class="w-full max-w-md bg-white p-4 rounded shadow mb-4">
                    <header class="font-semibold mb-2">
                        Материалы
                    </header>
                    <ul class="ml-6 list-disc">
                        <li>Материал_1</li>
                        <li>Материал_2</li>
                    </ul>
                </div>
                <div class="w-full max-w-md bg-white p-4 rounded shadow mb-4">
                    <header class="font-semibold mb-2">
                        Задания
                    </header>
                    <ul class="ml-6 list-disc">
                        <li>Задание_1</li>
                        <li>Задание_2</li>
                    </ul>
                </div>
                <div class="w-full max-w-md bg-white p-4 rounded shadow">
                    <header class="font-semibold mb-2">
                        Мероприятия
                    </header>
                    <ul class="ml-6 list-disc">
                        <li>Мероприятие_1</li>
                        <li>Мероприятие_2</li>
                    </ul>
                </div>
            </div>
            <footer class="flex justify-center w-full">
                <button class="text-blue-700 w-full bg-neutral-200 py-2 px-4 rounded-md hover:text-white hover:bg-neutral-400 hover:scale-95 transition-transform duration-300">
                    Открыть чат
                </button>
            </footer>
            <GroupEdit v-if="showGroupEdit" @close="showGroupEdit = false"></GroupEdit>
        </div>

        <!-- Правая колонка -->
        <div class="flex flex-col items-end">
            <button
                type="button"
                @click="showGroupEdit = true"
                class="text-gray-500 hover:text-gray-800 mb-4">
                Настройки группы
            </button>
            <div
                @click="showGroupMembersForm = true"
                class="grow text-sm underline underline-offset-4 decoration-1 decoration-black decoration-dotted text-gray-500 hover:text-gray-800 cursor-pointer mb-4">
                16 участников
            </div>
            <div>
                <button type="button"
                    @click=""
                    class="text-sm italic text-gray-400 hover:text-red-600">
                    Покинуть группу
                </button>
            </div>
        </div>

        <!-- Компонент участников группы -->
        <GroupMembers v-if="showGroupMembersForm" @close="showGroupMembersForm = false"></GroupMembers>
    </div>
</template>

<script>
import { ref } from "vue";
import GroupMembers from "@/components/messages/GroupsView/GroupMembersInSection.vue";
import GroupEdit from "@/components/messages/GroupsEdit/GroupsEdit.vue";
import { useRouter } from "vue-router";

export default {
    name: 'GroupView',
    components: {
        GroupMembers,
        GroupEdit
    },
    setup() {
        const showGroupEdit = ref(false);
        const showGroupMembersForm = ref(false);
        const router = useRouter();

        return {
            showGroupEdit,
            showGroupMembersForm,
            router
        }
    }
}
</script>
