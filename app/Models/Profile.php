<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'profiles';

    protected $fillable = [
        'firstname', 'secondname', 'phone_number', 'city'
    ];

    protected $guarded = [];

    protected $hidden = [];
}
