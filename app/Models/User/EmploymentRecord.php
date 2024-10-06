<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentRecord extends Model
{
    use HasFactory;
    protected $table = 'employment_records';

    protected $fillable = [
        'company_name', 'position', 'start_date', 'end_date'
    ];

    protected $guarded = [];
    protected $hidden = [];

    // Работа с пользователем
    public function user() {
        return $this->belongsTo(User::class);
    }
}
