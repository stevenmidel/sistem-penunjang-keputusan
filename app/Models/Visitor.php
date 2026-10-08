<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'nama',
        'visitor_token',
        'visit_count',
        'first_visit',
        'last_visit',
    ];

    protected $casts = [
        'first_visit' => 'datetime',
        'last_visit' => 'datetime',
    ];
}
