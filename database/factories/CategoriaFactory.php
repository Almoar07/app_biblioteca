<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private static array $categorias = [
        'Ficción',
        'No ficción',
        'Ciencia ficción',
        'Fantasía',
        'Misterio',
        'Romance',
        'Terror',
        'Biografía',
        'Historia',
        'Autoayuda',
        'Aventura',
        'Poesía',
        'Drama',
        'Infantil',
        'Juvenil',
    ];
    public function definition(): array
    {
        return [
            'nombre_categoria' => array_shift(self::$categorias),
            'descripcion_categoria' => $this->faker->sentence(),
            'deleted_by' => null,
            'created_by' => "Factory", // Assuming 1 is the ID of the user creating the category
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null, // Soft delete field, initially null
        ];
    }
}
