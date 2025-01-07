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
        <div class="flex flex-col items-center justify-center">
            <header class="font-semibold text-2xl italic text-indigo-800 mb-4">
                {{ currentGroupName }}
            </header>
            <div class="grow w-full justify-center">
                <div class="w-full flex flex-col bg-white p-4 rounded shadow mb-4">
                    <header class="font-semibold mb-2">
                        Материалы
                    </header>
                    <ul class="ml-6">
                        <li v-for="(materialSection, materialSectionIndex) in groupData.materialSections" :key="materialSectionIndex">
                            <header class="font-semibold">
                                {{ materialSection.sectionName }}:
                            </header>
                            <ul class="list-disc ml-12">
                                <li v-for="(material, materialIndex) in materialSection.materials" :key="materialIndex"
                                    @click="previewFile('materials', materialSection.sectionName, material.name)"
                                    class="hover:underline hover:underline-offset-4 hover:decoration-blue-400 hover:decoration-2 hover:italic hover:cursor-pointer">
                                    {{ material.name }}
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
<!--                <div class="w-full bg-white p-4 rounded shadow mb-4">-->
<!--                    <header class="font-semibold mb-2">-->
<!--                        Задания-->
<!--                    </header>-->
<!--                    <ul class="ml-6">-->
<!--                        <li v-for="(task, index) in groupData.taskSections.directories" :key="index">-->
<!--                            <header>-->
<!--                                {{ task }}-->
<!--                            </header>-->
<!--                            <ul class="list-disc ml-12">-->
<!--                                <li v-for="(sub_task, subIndex) in groupData.taskSections.links[index]" :key="sub_task.id"-->
<!--                                    @click="previewFile(groupData.materials.paths[index], sub_task.link)"-->
<!--                                    class="hover:underline hover:underline-offset-4 hover:decoration-blue-400 hover:decoration-2 hover:italic hover:cursor-pointer">-->
<!--                                    {{ sub_task.link }}-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
                <div class="w-full bg-white p-4 rounded shadow">
                    <header class="font-semibold mb-2">
                        Мероприятия
                    </header>
                    <ul class="ml-6 list-disc">
                        <li>Мероприятие_1</li>
                        <li>Мероприятие_2</li>
                    </ul>
                </div>
            </div>
            <footer class="flex justify-center w-full"
                v-if="showChat">
                <button class="text-blue-700 w-full bg-neutral-200 py-2 px-4 rounded-md hover:text-white hover:bg-neutral-400 hover:scale-95 transition-transform duration-300">
                    Открыть чат
                </button>
            </footer>
            <GroupEdit :groupId="currentGroupId" v-if="showGroupEdit" @close="showGroupEdit = false"></GroupEdit>
        </div>

        <!-- Правая колонка -->
        <div class="flex flex-col items-end">
            <button
                v-if="role === 'admin'"
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
import { onMounted, ref } from "vue";
import GroupMembers from "@/components/messages/GroupsView/GroupMembersInSection.vue";
import GroupEdit from "@/components/messages/GroupsEdit/GroupsEdit.vue";
import { useRouter } from "vue-router";
import { showNotification } from "@/notifications.js";
import { renderAsync } from 'docx-preview';

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
        const showChat = ref(true)
        const role = ref('user')
        const groupData = ref({
            groupName: '',
            countUsers: 0,
            materialSections: [
                // {
                //     sectionName: '',
                //     materials: [
                //         {
                //             name: '',
                //             file: ''
                //         }
                //     ]
                // }
            ],
            taskSections: []
        });
        const currentGroupId = ref(props.groupId || localStorage.getItem('groupId'));
        const currentGroupName = ref(props.groupName || localStorage.getItem('groupName'));


        // Получение данных группы: материалы, задания и прочее
        const fetchGroupData = async () => {
            try {
                const groupId = currentGroupId.value;
                const timestamp = new Date().getTime();
                let response_count_users = await axios.get(`/group/${groupId}/countUsers?ts=${timestamp}`);
                let response_material_data = await axios.get(`/group/${groupId}/getMaterialData?ts=${timestamp}`);
                let response_isChat = await axios.get(`/group/${groupId}/checkChat?ts=${timestamp}`);
                let response_role = await axios.get(`/group/${groupId}/checkRole?ts=${timestamp}`);

                groupData.value.countUsers = response_count_users.data.countUsers || 0;
                groupData.value.materialSections = response_material_data.data.materialSections || [];
                showChat.value = response_isChat.data.chatIsOpen || true;
                role.value = response_role.data.role || 'user';
            } catch (e) {
                if (e.response) {
                    console.log(e.response.data);
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

        // Предпросмотр для pdf, скачивание для doc-файлов
        const previewFile = async (category, section, material) => {
            try {
                let path = `/group/${currentGroupId.value}/preview/${category}/${section}/${material}`;
                const response = await axios.get(path, { responseType: 'blob' });

                const contentType = response.headers['content-type'];
                const fileURL = URL.createObjectURL(response.data);

                if (contentType === 'application/pdf') {
                    const newWindow = window.open(fileURL, '_blank');
                    if (!newWindow) {
                        alert('Please allow popups for this site to view the PDF.');
                    } else {
                        newWindow.onload = () => {
                            URL.revokeObjectURL(material);
                        };
                    }
                } else {
                    const link = document.createElement('a');
                    link.href = fileURL;
                    link.setAttribute('download', material);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    URL.revokeObjectURL(fileURL);
                }
            } catch (error) {
                console.error('Ошибка при предпросмотре файла:', error);
            }
        };

        return {
            showGroupEdit,
            showGroupMembersForm,
            router,
            groupData,
            currentGroupId,
            currentGroupName,
            previewFile,
            showChat,
            role
        }
    }
}
</script>
