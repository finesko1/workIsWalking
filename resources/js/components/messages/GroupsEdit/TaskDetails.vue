<template>
    <div class="task-details fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg w-2/3 h-3/4 overflow-y-auto flex flex-col">
            <h2 class="text-xl text-center font-semibold mb-4">Детали задания</h2>
            <div class="flex-1">
                <div class="mb-4">
                    <strong>Раздел:</strong> {{ sectionName }}
                </div>
                <div class="mb-4">
                    <strong>Задание:</strong> {{ taskName }}
                </div>
                <div class="mb-4">
                    <strong>Пользователи с доступом:</strong>
                    <ul>
                        <li v-for="user in flattenedUsers" :key="user.id" class="max-h-60 overflow-y-auto flex items-center mb-2">
                            <img v-if="user.image_url" :src="user.image_url" alt="User Image"
                                 class="rounded-full w-8 h-8 object-cover" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-8 h-8 text-white border border-cyan-900 bg-cyan-800 p-1 rounded-full">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span>{{ user.first_name + ' ' + user.second_name }}</span>
                            <div v-if="user.files && user.files.length > 0" class="ml-4">
                                <strong>Решение:</strong>
                                <ul>
                                    <li v-for="(file, index) in user.files" :key="index">
                                        <a :href="file" target="_blank" class="text-blue-600 underline">{{ file }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex justify-center mt-auto">
                <button @click="goBack" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                    Вернуться к группе
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';

export default {
    name: 'TaskDetails',
    props: {
        sectionName: String,
        taskName: String,
        users: Array,
        groupId: String
    },
    setup(props, {emit}) {
        const router = useRouter();
        const groupId = ref(props.groupId || localStorage.getItem('groupId'));

        const flattenedUsers = computed(() => {
            return props.users.map(user => ({
                ...user.userData,
                files: user.files // Add files to user data
            }));
        });

        const goBack = () => {
            emit('close');
        };

        return {
            goBack,
            flattenedUsers,
            sectionName: props.sectionName,
            taskName: props.taskName,
        };
    }
};
</script>
