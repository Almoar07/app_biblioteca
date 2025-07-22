<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVO = 'activo';
    case INACTIVO = 'inactivo';
    case BLOQUEADO = 'bloqueado';
}
