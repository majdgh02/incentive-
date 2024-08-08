<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->firstName(),
            'id_num' => fake()->unique()->numerify('###########'),
            'address' => fake()->address(),
            'home_num' => fake()->e164PhoneNumber(),
            'phone_num' => fake()->e164PhoneNumber(),
            'birth' => fake()->date('y-m-d'),
            'join' => fake()->date('y-m-d'),
            'edu' => fake()->word(),
            'section' => fake()->jobTitle(),
            'username' => fake()->unique()->userName(),
            'password' => fake()->password(6,8),
            'status' => 1
        ];
    }
}
