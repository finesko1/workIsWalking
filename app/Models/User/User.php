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
        'password', 'remember_token', 'id', 'email', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at'
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
        return $this->hasOne(PersonalData::class, 'user_id');
    }
    public function employmentRecord() : HasOne
    {
        return $this->hasOne(EmploymentRecord::class);
    }

    public function friends() {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted');
    }

    public function pendings() {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'pending');
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'follower');
    }

    public function followings() {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'following');
    }

    public function blocked() {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivotIn('status', ['blocked', 'blockIt'])
            ->where('users.id', '!=', auth()->id());
    }
}
