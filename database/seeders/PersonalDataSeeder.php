<?php

namespace Database\Seeders;

use App\Models\User\PersonalData;
use App\Models\User\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            PersonalData::factory()->create([
                'user_id' => $user->id,
                // остальные данные вводятся фабрикой
            ]);
        }
    }
}
