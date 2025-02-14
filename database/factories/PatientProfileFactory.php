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
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastname(),
            'birthdate' => fake()->date(),
            'contact_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'is_pwd' => fake()->boolean(),
            'pwd_number' => Str::random(10),
        ];
    }
}
