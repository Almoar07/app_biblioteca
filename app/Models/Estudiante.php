<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Livewire\Features\SupportConsoleCommands\Commands\DeleteCommand;

class Estudiante extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'rut_estudiante';



    protected $table = 'estudiantes';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'rut_estudiante',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'direccion',
        'comuna_estudiante',
        'curso',
        'estado',
        'email',
        'telefono',
        'deleted_by',
        'created_by',

    ];

    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_estudiante', 'id_comuna');
    }
}
