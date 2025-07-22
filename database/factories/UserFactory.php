<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laragear\Rut\Rut;
use Laragear\Rut\Facades\Generator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rut_usuario' => Generator::makeOne(),
            'name' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'lastname2' => fake()->lastName(),
            'birthday' => fake()->date(),
            'phone' => fake()->numerify('+56 9 #### ####'),
            'tipo_usuario' => fake()->randomElement(['admin', 'bibliotecario']),
            'status' => 'activo',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
