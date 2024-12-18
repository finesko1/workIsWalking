<template>
    <div>
        <ul>
            <li>
                <div class="flex-col p-1 w-full border border-black rounded-md text-center text-balance">
                    <div
                        @click = "goToGroup"
                        class="p-2 flex justify-center hover:rounded-md hover:shadow-inner hover:scale-95 transition-transform duration-200 cursor-pointer">
                        Основы программирования на языке Python
                    </div>
                    <div
                        @click = "showGroupMembersForm = true"
                        class="flex justify-end text-sm font-light underline underline-offset-4 decoration-1 decoration-black decoration-dotted hover:italic cursor-pointer">
                        16 участников
                    </div>
                </div>
            </li>
        </ul>
        <GroupMembers v-if="showGroupMembersForm" @close="showGroupMembersForm = false"></GroupMembers>
    </div>
</template>

<script>
import GroupMembers from "@/components/messages/GroupsView/GroupMembersInSection.vue"
import GroupView from "@/components/messages/GroupsView/GroupView.vue"
import {ref} from "vue";
import router from "@/router/index.js";

    export default {
        name: 'GroupsView',
        components: {
            GroupMembers,
            GroupView
        },
        setup(props, { emit }) {

            const showGroupMembersForm = ref(false);
            const showGroupForm = ref(false)

            const goToGroup = () => {
                emit('close')
                router.push('/groups/myGroup')
            }
            return {
                showGroupMembersForm,
                showGroupForm,
                goToGroup,
                router
            }
        }
    }
</script>
