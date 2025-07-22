<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ejemplar;
use App\Models\Libro;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ejemplar>
 */
class EjemplarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'id_libro' => Libro::inRandomOrder()->first()->id_libro,
            'codigo_barras' => $this->faker->unique()->ean13,
            'ubicacion_estante' => $this->faker->word,
            'status' => "disponible",
            /* 'fecha_prestamo' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'fecha_devolucion_esperada' => $this->faker->dateTimeBetween('now', '+1 month'), */
            'created_by' => "Factory", // Asignar un usuario por defecto, puedes cambiarlo según tu lógica
            'deleted_by' => null, // Inicialmente no está eliminado
            'fecha_ingreso' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
