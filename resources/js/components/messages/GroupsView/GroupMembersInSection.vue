<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20 h-full">
        <div class="flex-col p-4 bg-white rounded-xl min-w-min relative">
            <header class="flex justify-center">
                <h2 class="justify-center mx-10">
                    Участники группы
                </h2>
                <button type='button'
                        @click="$emit('close')"
                        class='absolute top-2 right-3'>
                    &#x2715;
                </button>
            </header>
            <ul>
                <li>
                    Иван иваныч
                </li>
                <li>
                    Иван иваныч
                </li>
                <li>
                    Иван иваныч
                </li>
                <li>
                    Иван иваныч
                </li>
                <li>
                    Иван иваныч
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'GroupMembers',
    setup() {

    }
}
</script>
