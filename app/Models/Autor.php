<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Libro;

class Autor extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_autor';
    protected $table = 'autores';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'nacionalidad',
        'delete_by',
        'created_by',
    ];

    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_autor');
    }

    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }


    protected static function booted()
    {
        static::deleting(function ($autor) {
            if ($autor->isForceDeleting()) {
                $autor->libros()->forceDelete();
            } else {
                $autor->libros()->delete();
            }
        });
    }
}
