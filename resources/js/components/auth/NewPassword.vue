<template>
    <div class="flex justify-center items-center max-w-2xl w-full p-8 shadow-lg rounded-lg bg-gray-350">
        <h1>Смена пароля</h1>
        <div>
            <label for="login" class="block text-sm font-medium text-gray-700">Логин</label>
            <input id="login" type="text" placeholder="Введите пароль" disabled
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
            <input id="password" type="password" placeholder="Введите пароль"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
        </div>

        <div>
            <label for="confirm-password" class="block text-sm font-medium text-gray-700">Проверка пароля</label>
            <input id="confirm-password" type="password" placeholder="Подтвердите пароль"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm hover:shadow-md" />
        </div>
<!--    Логика восстановления-->
        <div>
            <button type="submit"
                    class="w-full px-4 py-2 bg-cyan-600 text-white font-semibold rounded-lg shadow-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-opacity-50 transition hover:scale-95  duration-200 ease-in">
                Войти
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'NewPassword',
    };
</script>
