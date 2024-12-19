<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['group_id', 'info'];

    public function all_materials() {
        return $this->belongsToMany(Material::class, 'material_link', 'id', 'material_id');
    }
}
