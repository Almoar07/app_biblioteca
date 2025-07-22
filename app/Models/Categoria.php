<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;



    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre_categoria',
        'descripcion_categoria',
        'deleted_by',
        'created_by',
    ];
}
