<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Autor;
use App\Models\Estudiante;
use App\Models\Categoria;
use App\Models\Editorial;
use App\Models\Libro;
use App\Models\Comuna;
use App\Models\Ejemplar;
use Illuminate\Support\Carbon;



// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Mi cuenta admin
        User::factory()->create([
            'rut_usuario' => '18.334.671-7',
            'name' => 'Alejandro',
            'lastname' => 'Moya',
            'lastname2' => 'Arjona',
            'birthday' => '1992-11-07',
            'phone' => '+56962434326',
            'tipo_usuario' => 'admin',
            'status' => 'activo',
            'password' => bcrypt('amoya123'), // password
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'email' => 'amoya@bibliolite.cl',
        ]);
        // Crear un bibliotecario
        User::factory()->create([
            'rut_usuario' => '18.374.365-1',
            'name' => 'Pepe',
            'lastname' => 'Bibliotecario',
            'lastname2' => 'Do Santos',
            'birthday' => '1993-03-17',
            'phone' => '1231231234',
            'tipo_usuario' => 'bibliotecario',
            'status' => 'activo',
            'password' => bcrypt('elpepe123'), // Contraseña para el bibliotecario
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'email' => 'pepebibliotecario@bibliolite.com',
        ]);

        User::factory(3)->create();

        $comunas = [
            ['nombre_comuna' => 'Chépica', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'Chimbarongo', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'Codegua', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Coinco', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Coltauco', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Doñihue', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Graneros', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'La Estrella', 'provincia' => 'Cardenal Caro'],
            ['nombre_comuna' => 'Las Cabras', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Litueche', 'provincia' => 'Cardenal Caro'],
            ['nombre_comuna' => 'Lolol', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'Machalí', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Malloa', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Marchihue', 'provincia' => 'Cardenal Caro'],
            ['nombre_comuna' => 'Mostazal', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Nancagua', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'Navidad', 'provincia' => 'Cardenal Caro'],
            ['nombre_comuna' => 'Olivar', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Palmilla', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'Paredones', 'provincia' => 'Cardenal Caro'],
            ['nombre_comuna' => 'Peralillo', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'Peumo', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Pichidegua', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Pichilemu', 'provincia' => 'Cardenal Caro'],
            ['nombre_comuna' => 'Placilla', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'Pumanque', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'Quinta de Tilcoco', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Rancagua', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Rengo', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Requínoa', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'San Fernando', 'provincia' => 'Colchagua'],
            ['nombre_comuna' => 'San Vicente', 'provincia' => 'Cachapoal'],
            ['nombre_comuna' => 'Santa Cruz', 'provincia' => 'Colchagua']
        ];

        DB::table('comunas')->insert($comunas);
        //Crea estudiantes de prueba
        Estudiante::factory()->create([
            'rut_estudiante' => '19.849.608-1',
            'nombres' => 'Pepe',
            'apellido_paterno' => 'Lector',
            'apellido_materno' => 'Compulsivo',
            'fecha_nacimiento' => '2000-01-01',
            'direccion' => 'Calle 123',
            'comuna_estudiante' => 31,
            'curso' => '4° Medio F',
            'estado' => 'activo',
            'email' => 'R7eOw@example.com',
            'telefono' => '123456789',
            'deleted_by' => null,
            'created_by' => 'Factory'
        ]);
        Estudiante::factory(4)->create();

        $autoresData = [
            [
                'nombre' => 'Gabriel',
                'apellido_paterno' => 'García',
                'apellido_materno' => 'Márquez',
                'fecha_nacimiento' => '1927-03-06',
                'nacionalidad' => 'Colombia'
            ],
            [
                'nombre' => 'Isabel',
                'apellido_paterno' => 'Allende',
                'apellido_materno' => 'Llona',
                'fecha_nacimiento' => '1942-08-02',
                'nacionalidad' => 'Chile'
            ],
            [
                'nombre' => 'Jorge Luis',
                'apellido_paterno' => 'Borges',
                'apellido_materno' => 'Acevedo',
                'fecha_nacimiento' => '1899-08-24',
                'nacionalidad' => 'Argentina'
            ],
            [
                'nombre' => 'Mario',
                'apellido_paterno' => 'Vargas',
                'apellido_materno' => 'Llosa',
                'fecha_nacimiento' => '1936-03-28',
                'nacionalidad' => 'Perú'
            ],
            [
                'nombre' => 'Julio',
                'apellido_paterno' => 'Cortázar',
                'apellido_materno' => 'Descotte',
                'fecha_nacimiento' => '1914-08-26',
                'nacionalidad' => 'Argentina'
            ],
            [
                'nombre' => 'Laura',
                'apellido_paterno' => 'Esquivel',
                'apellido_materno' => 'Valdés',
                'fecha_nacimiento' => '1950-09-30',
                'nacionalidad' => 'México'
            ],
            [
                'nombre' => 'Juan',
                'apellido_paterno' => 'Rulfo',
                'apellido_materno' => 'Preciado',
                'fecha_nacimiento' => '1917-05-16',
                'nacionalidad' => 'México'
            ],
            [
                'nombre' => 'Pablo',
                'apellido_paterno' => 'Neruda',
                'apellido_materno' => 'Basoalto',
                'fecha_nacimiento' => '1904-07-12',
                'nacionalidad' => 'Chile'
            ],
            [
                'nombre' => 'Octavio',
                'apellido_paterno' => 'Paz',
                'apellido_materno' => 'Lozano',
                'fecha_nacimiento' => '1914-03-31',
                'nacionalidad' => 'México'
            ],
            [
                'nombre' => 'Miguel',
                'apellido_paterno' => 'Delibes',
                'apellido_materno' => 'Setién',
                'fecha_nacimiento' => '1920-10-17',
                'nacionalidad' => 'España'
            ]
        ];

        $editorialesData = [
            ['nombre_editorial' => 'Penguin Random House'],
            ['nombre_editorial' => 'Plaza & Janés Editores, S.a.'],
            ['nombre_editorial' => 'Lumen'],
            ['nombre_editorial' => 'Real Academia Española'],
            ['nombre_editorial' => 'Rm Verlag, S.l'],
            ['nombre_editorial' => 'Universidad Diego Portales'],
            ['nombre_editorial' => 'Fondo De Cultura Económica'],
            ['nombre_editorial' => 'Tusquets Editores'],
            ['nombre_editorial' => 'Austral'],
            ['nombre_editorial' => 'Debolsillo']
        ];

        $categoriasData = [
            ['nombre_categoria' => 'Novela'],
            ['nombre_categoria' => 'Novela chilena'],
            ['nombre_categoria' => 'Cuentos'],
            ['nombre_categoria' => 'Poesía'],
            ['nombre_categoria' => 'Biografía'],
            ['nombre_categoria' => 'Ciencia Ficción'],
            ['nombre_categoria' => 'Ensayo'],
            ['nombre_categoria' => 'Historia'],
            ['nombre_categoria' => 'Thriller'],
            ['nombre_categoria' => 'Romance']
        ];

        // Insertar autores
        foreach ($autoresData as $data) {
            Autor::firstOrCreate(
                ['nombre' => $data['nombre'], 'apellido_paterno' => $data['apellido_paterno']],
                $data
            );
        }

        // Insertar editoriales
        foreach ($editorialesData as $data) {
            Editorial::firstOrCreate(
                ['nombre_editorial' => $data['nombre_editorial']],
                $data
            );
        }

        // Insertar categorías
        foreach ($categoriasData as $data) {
            Categoria::firstOrCreate(
                ['nombre_categoria' => $data['nombre_categoria']],
                $data
            );
        }
        // Arreglo de Libros con ISBNs solo numéricos
        $librosData = [
            [
                'titulo' => 'Cien años de soledad. - 4. edición',
                'isbn' => '9788497592208',
                'id_autor' => 1, // Gabriel García Márquez
                'id_editorial' => 1, // Alfaguara
                'anio_publicacion' => 1967,
                'id_categoria' => 1, // Novela
                'sinopsis' => 'La historia de la familia Buendía a lo largo de varias generaciones en el pueblo de Macondo.',
                'portada' => 'portadas/100anos.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'La casa de los espíritus',
                'isbn' => '9788401341908',
                'id_autor' => 2,
                'id_editorial' => 2,
                'anio_publicacion' => 2007,
                'id_categoria' => 2, // Novela
                'sinopsis' => 'Una saga familiar que abarca varias décadas, mezclando lo personal con lo político en un país latinoamericano anónimo.',
                'portada' => 'portadas/espiritus.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'Ficciones',
                'isbn' => '9789585404359', // ISBN de 13 dígitos
                'id_autor' => 3, // Jorge Luis Borges
                'id_editorial' => 3, // Debolsillo
                'anio_publicacion' => 2019,
                'id_categoria' => 3, // Cuento
                'sinopsis' => 'Colección de cuentos cortos que exploran temas como los laberintos, los sueños, las bibliotecas y la identidad.',
                'portada' => 'portadas/ficciones.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'La ciudad y los perros',
                'isbn' => '9788420412337', // ISBN de 13 dígitos
                'id_autor' => 4, // Mario Vargas Llosa
                'id_editorial' => 4,
                'anio_publicacion' => 2012,
                'id_categoria' => 1, // Novela
                'sinopsis' => 'La vida en el Colegio Militar Leoncio Prado y las complejas relaciones entre sus cadetes.',
                'portada' => 'portadas/laciudadylosperros.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'Rayuela',
                'isbn' => '9789589016787', // ISBN de 13 dígitos
                'id_autor' => 5, // Julio Cortázar
                'id_editorial' => 1, // Penguin Random House
                'anio_publicacion' => 1963,
                'id_categoria' => 1, // Novela
                'sinopsis' => 'Una antinovela que puede ser leída de varias formas, explorando la vida bohemia en París y Buenos Aires.',
                'portada' => 'portadas/rayuela.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'Como agua para chocolate',
                'isbn' => '9788466329088', // ISBN de 13 dígitos
                'id_autor' => 6, // Laura Esquivel
                'id_editorial' => 1, // Penguin Random House
                'anio_publicacion' => 2020,
                'id_categoria' => 1, // Novela
                'sinopsis' => 'La historia de Tita, una mujer que expresa sus emociones a través de la comida, en el México revolucionario.',
                'portada' => 'portadas/aguachocolate.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'Pedro Páramo',
                'isbn' => '9788493442606', // ISBN de 13 dígitos
                'id_autor' => 7, // Juan Rulfo
                'id_editorial' => 5, // Fondo de Cultura Económica
                'anio_publicacion' => 2016,
                'id_categoria' => 1, // Novela
                'sinopsis' => 'Un viaje a Comala en busca de un padre, revelando un pueblo habitado por fantasmas y recuerdos.',
                'portada' => 'portadas/paramo.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'Veinte poemas de amor y una canción desesperada',
                'isbn' => '9789563140514', // ISBN de 13 dígitos
                'id_autor' => 8, // Pablo Neruda
                'id_editorial' => 6,
                'anio_publicacion' => 1924,
                'id_categoria' => 4,
                'sinopsis' => 'Una colección de poemas de amor que exploran la pasión, la melancolía y la naturaleza.',
                'portada' => 'portadas/20poemasneruda.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'El laberinto de la soledad',
                'isbn' => '9786071633606', // ISBN de 13 dígitos
                'id_autor' => 9, // Octavio Paz
                'id_editorial' => 7, // Fondo de Cultura Económica
                'anio_publicacion' => 1950,
                'id_categoria' => 7, // Ensayo
                'sinopsis' => 'Un profundo ensayo sobre la identidad, la historia y la cultura mexicana.',
                'portada' => 'portadas/laberintosoledad.jpg',
                'dias_maximos_prestamo' => 15
            ],
            [
                'titulo' => 'Los santos inocentes',
                'isbn' => '9788423353521', // ISBN de 13 dígitos
                'id_autor' => 10, // Miguel Delibes
                'id_editorial' => 9, // Grupo Planeta
                'anio_publicacion' => 2018,
                'id_categoria' => 1, // Novela
                'sinopsis' => 'Un retrato crudo y conmovedor de la vida de una familia de campesinos bajo un régimen opresivo.',
                'portada' => 'portadas/santosinocentes.jpg',
                'dias_maximos_prestamo' => 15
            ]
        ];

        // 3. Insertar Libros usando los IDs mapeados
        foreach ($librosData as $index => $libro) {
            $nuevoLibro = Libro::create([
                'titulo' => $libro['titulo'],
                'isbn' => $libro['isbn'],
                'id_autor' => $libro['id_autor'],
                'id_editorial' => $libro['id_editorial'],
                'anio_publicacion' => $libro['anio_publicacion'],
                'id_categoria' => $libro['id_categoria'],
                'sinopsis' => $libro['sinopsis'],
                'portada' => $libro['portada']
            ]);
            // Crear 3 ejemplares por libro
            for ($i = 1; $i <= 3; $i++) {
                Ejemplar::create([
                    'id_libro' => $nuevoLibro->id_libro,
                    'codigo_barras' => 'LIB' . $nuevoLibro->id_libro . 'E' . $i,
                    'ubicacion_estante' => 'Estante-' . $libro['id_categoria'],
                    'status' => 'disponible',
                    'fecha_ingreso' => now(),
                    'created_by' => 'Seeder',
                    'created_batch' => 'SeederLibrosConEjemplares'
                ]);
            }
        }

        $this->call([
            // Otros seeders...
            PrestamosSeeder::class,
        ]);
    }
}
