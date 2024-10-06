<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalData extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'personal_data';

    protected $fillable = [
        'user_id', 'first_name', 'second_name', 'phone_number', 'city'
    ];

    protected $guarded = [];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'user_id');
    }
}
