<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laragear\Rut\Rut;
use Laragear\Rut\Facades\Generator;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $niveles = [
            '7º Básico',
            '8º Básico',
            '1º Medio',
            '2º Medio',
            '3º Medio',
            '4º Medio',
        ];

        $letras = ['A', 'B', 'C', 'D', 'E', 'F'];

        $curso = Arr::random($niveles) . ' ' . Arr::random($letras);
        return [

            'rut_estudiante' => Generator::makeOne(),
            'nombres' => fake()->firstName() . ' ' . fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'fecha_nacimiento' => fake()->date,
            'direccion' => fake()->address(),
            'comuna_estudiante' => \App\Models\Comuna::inRandomOrder()->value('id_comuna'),
            'created_by' => "Factory",
            'curso' => $curso,
            'estado' => fake()->randomElement(['activo', 'inactivo', 'egresado', 'bloqueado']),
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->numerify('+56 9 #### ####'),
        ];
    }
}
