<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
//Работа с почтой
//Работа с Auth::

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

    // Для работы с таблицами личных данных и записями о работе
    public function personalData()
    {
        return $this->hasOne(\App\Models\User\PersonalData::class, 'user_id');
    }
    public function employmentRecord() : HasOne
    {
        return $this->hasOne(EmploymentRecord::class);
    }
}
