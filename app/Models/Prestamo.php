<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    //  
    protected $table = 'prestamos'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id_prestamo'; // Clave primaria de la tabla

    protected $fillable = [
        'id_ejemplar',
        'rut_estudiante',
        'id_bibliotecario',
        'fecha_prestamo',
        'fecha_devolucion_esperada',
        'status',
        'observaciones',
        'created_by',
        'deleted_by',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'rut_estudiante', 'rut_estudiante');
    }

    public function ejemplar()
    {
        return $this->belongsTo(Ejemplar::class, 'id_ejemplar', 'id_ejemplar');
    }

    public function bibliotecario()
    {
        return $this->belongsTo(User::class, 'id_bibliotecario');
    }
}
