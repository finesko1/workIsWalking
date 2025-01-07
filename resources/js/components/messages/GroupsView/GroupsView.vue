<template>
    <div class="flex flex-col">
        <ul v-if="isDataLoaded" class="flex flex-col">
            <li v-for="(group, index) in groupsData" :key="group.id" class="flex p-1">
                <div class="flex-col p-1 w-full border border-black rounded-md text-center text-balance">
                    <div
                        @click = "goToGroup(group.groupName, group.group_id)"
                        class="p-2 flex justify-center hover:rounded-md hover:shadow-inner hover:scale-95 transition-transform duration-200 cursor-pointer">
                        {{ group.groupName }}
                    </div>
                    <div
                        @click = "handleGroupMembers(group.group_id)"
                        class="flex justify-end text-sm font-light underline underline-offset-4 decoration-1 decoration-black decoration-dotted hover:italic cursor-pointer">
                        {{ group.countUsers }} участника(ов)
                    </div>
                </div>
            </li>
        </ul>
        <div v-else class="flex justify-center">
            <button type="button" class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-cyan-700 hover:bg-cyan-600 transition ease-in-out duration-150 cursor-not-allowed" disabled="">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Загрузка...
            </button>
        </div>
        <div v-if="isDataLoaded && groupsData.length === 0" class="flex justify-center my-4">
            <div>
                Вы не состоите в группах
            </div>
        </div>
        <GroupMembers :groupId=currentGroupId v-if="showGroupMembersForm" @close="showGroupMembersForm = false"></GroupMembers>
    </div>
</template>

<script>
import GroupMembers from "@/components/messages/GroupsView/GroupMembersInSection.vue"
import GroupView from "@/components/messages/GroupsView/GroupView.vue"
import {onMounted, ref} from "vue";
import router from "@/router/index.js";
import {showNotification} from "@/notifications.js";

    export default {
        name: 'GroupsView',
        components: {
            GroupMembers,
            GroupView
        },
        props: ['currentGroupId'],
        setup(props, { emit }) {
            const isDataLoaded = ref(false);
            const showGroupMembersForm = ref(false);
            const showGroupForm = ref(false)
            const groupsData = ref([]);
            const currentGroupId = ref(props.currentGroupId || localStorage.getItem('groupId'));
            onMounted(() => {
                fetchGroupData()
            });

            // Поиск информации о группах
            const fetchGroupData = async () => {
                try {
                    let response = await axios.get('/groups/all');
                    groupsData.value = response.data.groupsData || [];
                    await new Promise(resolve => setTimeout(resolve,200));
                    isDataLoaded.value = true;
                } catch (e) {
                    if (e.response.error){
                        showNotification(e.response.error, false, 1000)
                        isDataLoaded.value = false;
                    } else {
                        showNotification(e.message, false, 1000)
                        isDataLoaded.value = false;
                    }
                }
            }
            const handleGroupMembers = async (groupId) => {
                showGroupMembersForm.value = true;
                currentGroupId.value = groupId
            }

            const goToGroup = (group_name, group_id) => {
                emit('group-selected', group_name, group_id)
            }
            return {
                isDataLoaded,
                showGroupMembersForm,
                showGroupForm,
                goToGroup,
                groupsData,
                handleGroupMembers,
                currentGroupId
            }
        }
    }
</script>
