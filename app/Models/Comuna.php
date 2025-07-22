<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    //
    protected $primaryKey = 'id_comuna';

    protected $table = 'comunas';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_comuna',
        'nombre_comuna',
    ];
}
