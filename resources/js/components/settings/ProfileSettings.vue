<template>
    <form class="space-y-4 ml-8">
        <div class="flex items-center">
            <label for="name" class="w-1/3 text-right pr-4">Логин:</label>
            <input type="text" id="name" class="w-2/3 p-2 border border-gray-300 rounded" />
        </div>

        <div class="flex items-center">
            <label for="email" class="w-1/3 text-right pr-4">Почта:</label>
            <input type="email" id="email" class="w-2/3 p-2 border border-gray-300 rounded" />
        </div>

    </form>
</template>

<script>
export default {
    name: 'ProfileSettings',
}
</script>
