<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case BIBLIOTECARIO = 'bibliotecario';
    case INVITADO = 'invitado';
}
