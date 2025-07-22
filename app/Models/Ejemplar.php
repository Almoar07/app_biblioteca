<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Ejemplar extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_ejemplar';

    protected $table = 'ejemplares';
    protected $fillable = [
        'id_libro',
        'codigo_barras',
        'ubicacion_estante',
        'status', // disponible, prestado, reservado, etc.        
        'fecha_prestamo',
        'fecha_devolucion_esperada',
        'created_by',
        'deleted_by',
        'created_batch',
    ];
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro', 'id_libro');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_ejemplar', 'id_ejemplar');
    }
}
