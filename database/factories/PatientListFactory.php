<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientProfile>
 */
class PatientProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'firstname' => fake()->unique()->firstName(),
            'lastname' => fake()->unique()->lastname(),
            'birthdate' => fake()->date(),
            'contact_number' => fake()->unique()->phoneNumber(),
            'address' => fake()->unique()->address(),
            'is_pwd' => fake()->unique()->boolean(),
            'pwd_number' => Str::random(10),
        ];
    }
}
