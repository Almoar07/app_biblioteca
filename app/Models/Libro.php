<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Libro extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id_libro';



    protected $table = 'libros';
    protected $fillable = [
        'titulo',
        'isbn',
        'id_autor',
        'id_editorial',
        'anio_publicacion',
        'id_categoria',
        'sinopsis',
        'portada',
        'dias_maximos_prestamo',
        'created_by',
        'deleted_by',
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor', 'id_autor');
    }


    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'id_editorial', 'id_editorial');
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function ejemplares()
    {
        return $this->hasMany(Ejemplar::class, 'id_libro', 'id_libro');
    }

    public function prestamos()
    {
        return $this->hasManyThrough(
            Prestamo::class,       // Modelo destino
            Ejemplar::class,       // Modelo intermedio
            'id_libro',            // Foreign key en ejemplares (intermedio) que apunta a libro
            'id_ejemplar',         // Foreign key en prestamos que apunta a ejemplar
            'id_libro',            // Local key en libros
            'id_ejemplar'          // Local key en ejemplares
        );
    }
}
