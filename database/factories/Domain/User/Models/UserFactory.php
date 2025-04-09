<?php

namespace Database\Factories\Domain\User\Models;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '+1' . $this->faker->numerify('##########'),
            'country' => $this->faker->randomElement(['US', 'GB', 'DE', 'FR', 'IR']),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'introduction' => $this->faker->paragraph(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
        ];
    }
} 