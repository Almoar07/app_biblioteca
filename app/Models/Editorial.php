<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Editorial extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'editoriales';
    protected $primaryKey = 'id_editorial';

    protected $fillable = [
        'nombre_editorial',
        'deleted_by',
        'created_by',
    ];
}
