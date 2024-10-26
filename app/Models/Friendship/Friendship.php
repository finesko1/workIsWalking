<?php

namespace App\Models\Friendship;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $table = 'friendships';

    protected $fillable = [
        'user_id', 'friend_id', 'status'
    ];

    protected $hidden = [
        'user_id', 'friend_id', 'status', 'created_at', 'updated_at'
    ];
}
