<template>
    <div class="flex flex-col items-center justify-start">
        <div class='flex text-center'>{{ headForm }}</div>

        <div>
            <ul class="flex items-center justify-center p-2 gap-1 text-white">
                <li class="p-1 bg-cyan-600 rounded-xl">
                    <router-link to='/friendship/friends'>
                        Друзья
                    </router-link>
                </li>
                <li class="p-1 bg-cyan-600 rounded-xl">
                    <router-link to='/friendship/pendings'>
                        Заявки
                    </router-link>
                </li>
                <li class="p-1 bg-cyan-600 rounded-xl">
                    <router-link to='/friendship/followers'>
                        Подписчики
                    </router-link>
                </li>
                <li class="p-1 bg-cyan-600 rounded-xl">
                    <router-link to='/friendship/followings'>
                        Подписки
                    </router-link>
                </li>
                <li class="p-1 bg-cyan-600 rounded-xl">
                    <router-link to='/friendship/search'>
                        Поиск
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="w-full">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import { onMounted, ref, watch } from "vue";
    import { useRoute } from "vue-router";

    export default {
        name: 'Friendship',
        setup() {
            // const route = useRoute();
            const headForm = ref('Друзья');
            const route = useRoute();
            onMounted(async () => {

            });

            const updateHeadForm = () => {
                if (route.path === '/friendship/friends') {
                    headForm.value = 'Список друзей';
                } else if (route.path === '/friendship/search') {
                    headForm.value = 'Поиск друзей';
                } else if (route.path === '/friendship/pendings') {
                    headForm.value = 'Заявки';
                } else if (route.path === '/friendship/followers') {
                    headForm.value = 'Список подписчиков';
                } else if (route.path === '/friendship/followings') {
                    headForm.value = 'Список подписок';
                } else {
                    headForm.value = 'Люди';
                }
            };

            watch(() => route.path, () => {
                updateHeadForm();
            });

            return {
                headForm,
            }
        }
    }
</script>
