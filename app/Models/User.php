<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\Enums\UserStatus;
use App\Enums\UserType;

class User extends Authenticatable implements CanResetPasswordContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'rut_usuario',
        'name',
        'lastname',
        'lastname2',
        'email',
        'password',
        'tipo_usuario',
        'phone',
        'birthday',
        'status',
        'deleted_by',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'tipo_usuario' => UserType::class
        ];
    }

    public function isAdmin(): bool
    {
        return $this->tipo_usuario === UserType::ADMIN;
    }

    public function isLibrarian(): bool
    {
        return $this->tipo_usuario === UserType::BIBLIOTECARIO;
    }

    /* public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    } */
}
