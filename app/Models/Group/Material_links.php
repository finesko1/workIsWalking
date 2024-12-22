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
        'link'
    ];

    public function material() {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
