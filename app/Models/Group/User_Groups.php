<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Groups extends Model
{
    use HasFactory;
    protected $table = 'group_users';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['role', 'group_id', 'user_id'];

    //protected $hidden = ['id', 'role', 'created_at', 'updated_at', 'group_id', 'user_id'];
}
