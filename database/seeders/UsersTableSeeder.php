<?php

namespace Database\Seeders;

use App\Models\User\PersonalData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User\User;


class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Создание 10 случайных пользователей
        User::factory()->count(10)->create();
    }
}
