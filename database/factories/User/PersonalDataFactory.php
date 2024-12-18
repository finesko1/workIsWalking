<?php

namespace Database\Factories\User;

use App\Models\User\PersonalData;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonalData>
 */
class PersonalDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'first_name' => $this->faker->firstName($gender),
            'second_name' => $this->faker->lastName($gender),
        ];
    }
}
