<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employment_record extends Model
{
    use HasFactory;
    protected $table = 'employment_records';

    protected $fillable = [
        'company_name', 'position', 'start_date', 'end_date'
    ];

    protected $guarded = [];
    protected $hidden = [];
}
