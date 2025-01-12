<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material_links extends Model
{
    use HasFactory;

    protected $table = 'material_links';

    protected $fillable = [
        'material_id',
        'filename',
        'access_users'
    ];

    /**
     * Связь с моделью Material.
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /**
     * Связь с моделью TaskMaterialDeadline.
     */
    public function deadlines()
    {
        return $this->hasMany(TaskMaterialDeadline::class, 'material_link_id', 'id');
    }
}
