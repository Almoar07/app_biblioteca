<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Editorial>
 */
class EditorialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public static array $editoriales = [
        'Penguin Random House',
        'HarperCollins',
        'Simon & Schuster',
        'Hachette Book Group',
        'Macmillan Publishers',
        'Scholastic',
        'Grupo Planeta',
        'Editorial Santillana',
        'Ediciones SM',
        'Editorial Anagrama',
        'Tusquets Editores',
        'Alfaguara',
        'Ediciones Siruela',
        'Editorial Norma',
        'Editorial Planeta'
    ];

    public function definition(): array
    {
        return [
            'nombre_editorial' => $this->faker->unique()->randomElement(self::$editoriales),
            'deleted_by' => null, // Este campo puede ser nulo inicialmente
            'created_by' => "Factory",
            'created_at' => now(),
        ];
    }
}
