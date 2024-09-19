<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
//Работа с почтой
use Illuminate\Contracts\Auth\MustVerifyEmail;
//Работа с Auth::
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements AuthenticatableContract, MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;
// Имя таблицы, связанной с моделью (по умолчанию нижний регистр + мн. ч)
    protected $table = 'users';

    protected $fillable = [
        'login', 'email', 'password'
    ];
    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
