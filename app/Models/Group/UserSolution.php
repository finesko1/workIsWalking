<?php

namespace App\Models\Group;

use App\Models\TaskMaterialDeadline;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_material_deadline_id',
        'user_id',
        'solution_file',
        'submitted_at',
    ];

    public function taskMaterialDeadline()
    {
        return $this->belongsTo(TaskMaterialDeadline::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
