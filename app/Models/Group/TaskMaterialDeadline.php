<?php

namespace App\Models\Group;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMaterialDeadline extends Model
{
    use HasFactory;

    protected $table = 'task_material_deadlines';

    protected $fillable = [
        'material_link_id',
        'user_id',
        'deadline',
    ];

    /**
     * Связь с моделью Material_links.
     */
    public function materialLink()
    {
        return $this->belongsTo(Material_links::class, 'material_link_id');
    }

    /**
     * Связь с моделью User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
