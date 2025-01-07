<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $guarded = ['created_at', 'updated_at'];
    protected $fillable = ['group_id', 'section'];

    public function links() {
        return $this->hasMany(Material_links::class, 'material_id', 'id');
    }
}
