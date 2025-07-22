<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Editorial;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Libro>
 */
class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    /**
     * Lista de 20 libros reales con título e ISBN.
     */
    public static array $librosReales = [
        ['titulo' => 'Cien años de soledad', 'isbn' => '9780307474728'],
        ['titulo' => 'Don Quijote de la Mancha', 'isbn' => '9788491050297'],
        ['titulo' => '1984', 'isbn' => '9780451524935'],
        ['titulo' => 'El Principito', 'isbn' => '9780156013987'],
        ['titulo' => 'Rayuela', 'isbn' => '9788437602215'],
        ['titulo' => 'Fahrenheit 451', 'isbn' => '9781451673319'],
        ['titulo' => 'Crónica de una muerte anunciada', 'isbn' => '9781400034956'],
        ['titulo' => 'La sombra del viento', 'isbn' => '9788408172177'],
        ['titulo' => 'Orgullo y prejuicio', 'isbn' => '9780141439518'],
        ['titulo' => 'El amor en los tiempos del cólera', 'isbn' => '9780307389732'],
        ['titulo' => 'Matar a un ruiseñor', 'isbn' => '9780060935467'],
        ['titulo' => 'La casa de los espíritus', 'isbn' => '9780553383805'],
        ['titulo' => 'El nombre de la rosa', 'isbn' => '9780156001311'],
        ['titulo' => 'Pedro Páramo', 'isbn' => '9786070725774'],
        ['titulo' => 'Los detectives salvajes', 'isbn' => '9788433973975'],
        ['titulo' => 'El túnel', 'isbn' => '9789500420781'],
        ['titulo' => 'Ensayo sobre la ceguera', 'isbn' => '9780156007757'],
        ['titulo' => 'La tregua', 'isbn' => '9786073146538'],
        ['titulo' => 'El alquimista', 'isbn' => '9780062315007'],
        ['titulo' => 'Drácula', 'isbn' => '9780141439846'],
    ];

    public function definition(): array
    {
        return [

            'titulo' => self::$librosReales[array_rand(self::$librosReales)]['titulo'],
            'isbn' => fake()->isbn13(),
            'id_autor' => Autor::inRandomOrder()->value('id_autor'),
            'id_editorial' => Editorial::inRandomOrder()->value('id_editorial'),
            'anio_publicacion' => fake()->year(),
            'id_categoria' => Categoria::inRandomOrder()->value('id_categoria'),
            'sinopsis' => fake()->paragraph(),
            'portada' => "portadas/placeholder.png", // Placeholder image
            'created_by' => "Factory",

        ];
    }
}
