<template class="h-full">
    <div class="grid grid-cols-3 gap-4 p-4 min-h-full">
        <!-- Левая колонка -->
        <div class="flex justify-start items-start">
            <button
                type="button"
                @click="router.push('/groups')"
                class="text-gray-500 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                </svg>
            </button>
        </div>

        <!-- Центральная колонка -->
        <div class="flex flex-col items-center">
            <header class="font-semibold text-2xl italic text-indigo-800 mb-4">
                {{ currentGroupName }}
            </header>
            <div class="grow w-full">
                <div class="w-full max-w-md bg-white p-4 rounded shadow mb-4">
                    <header class="font-semibold mb-2">
                        Материалы
                    </header>
                    <ul class="ml-6">
                        <li v-for="(material, index) in groupData.materials.directories" :key="index">
                            <header>
                                {{ material }}
                            </header>
                            <ul class="list-disc ml-12">
                                <li v-for="(sub_material, subIndex) in groupData.materials.links[index]" :key="sub_material.id"
                                    @click="previewFile(groupData.materials.paths[index], sub_material.link)"
                                    class="hover:underline hover:underline-offset-4 hover:decoration-blue-400 hover:decoration-2 hover:italic hover:cursor-pointer">
                                    {{ sub_material.link }}
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="w-full max-w-md bg-white p-4 rounded shadow mb-4">
                    <header class="font-semibold mb-2">
                        Задания
                    </header>
                    <ul class="ml-6">
                        <li v-for="(task, index) in groupData.tasks.directories" :key="index">
                            <header>
                                {{ task }}
                            </header>
                            <ul class="list-disc ml-12">
                                <li v-for="(sub_task, subIndex) in groupData.tasks.links[index]" :key="sub_task.id">
                                    {{ sub_task.link }}
                                </li>
                            </ul>
                        </li>
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
                {{ groupData.countUsers }} участника(ов)
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
        <GroupMembers :groupId=currentGroupId v-if="showGroupMembersForm" @close="showGroupMembersForm = false"></GroupMembers>
    </div>
</template>

<script>
import {onMounted, ref} from "vue";
import GroupMembers from "@/components/messages/GroupsView/GroupMembersInSection.vue";
import GroupEdit from "@/components/messages/GroupsEdit/GroupsEdit.vue";
import { useRouter } from "vue-router";
import {showNotification} from "@/notifications.js";

export default {
    name: 'GroupView',
    components: {
        GroupMembers,
        GroupEdit
    },
    props: ['groupId', 'groupName'],
    setup(props) {
        const showGroupEdit = ref(false);
        const showGroupMembersForm = ref(false);
        const router = useRouter();
        const groupData = ref({
            groupName : '',
            countUsers: 0,
            materials: {},
            tasks: {}
        });
        const currentGroupId = ref(props.groupId || localStorage.getItem('groupId'));
        const currentGroupName = ref(props.groupName || localStorage.getItem('groupName'));

        // Сохраняем groupId в localStorage, если он передан через props
        if (props) {
            localStorage.setItem('groupId', props.groupId);
            localStorage.setItem('groupName', props.groupName);
        }

        const fetchGroupData = async () => {
            try {
                const groupId = currentGroupId.value;
                const timestamp = new Date().getTime();
                let response = await axios.get(`/groups/${groupId}/countUsers?ts=${timestamp}`);
                let response_materials = await axios.get(`/group/${groupId}/materials?ts=${timestamp}`);
                let response_tasks = await axios.get(`/group/${groupId}/tasks?ts=${timestamp}`);
                groupData.value.countUsers = response.data.countUsers;
                groupData.value.materials = response_materials.data;
                groupData.value.tasks = response_tasks.data;
            } catch (e) {
                if (e.response) {
                    showNotification(e.response.error, 0, 1000);
                    console.log(e.response.errors);
                } else {
                    console.log(e.message);
                }
            }
        };

        onMounted(() => {
            if (!currentGroupId.value) {
                console.error('Group ID отсутствует');
            } else {
                fetchGroupData();
            }
        });

        const previewFile = async (material, sub_material) => {
            let path = material + '/' + sub_material;
            try {
                const groupId = currentGroupId.value
                const response = await axios.get(`/group/${groupId}/${path}`, {
                    responseType: 'blob'
                });

                const fileURL = window.URL.createObjectURL(new Blob([response.data]));
                const fileWindow = window.open(fileURL);
                if (!fileWindow) {
                    alert('Не удалось открыть файл для предпросмотра. Пожалуйста, разрешите всплывающие окна.');
                }
            } catch (error) {
                console.error('Ошибка при предпросмотре файла:', error);
            }
        }

        return {
            showGroupEdit,
            showGroupMembersForm,
            router,
            groupData,
            currentGroupId,
            currentGroupName,
            previewFile
        }
    }
}
</script>
