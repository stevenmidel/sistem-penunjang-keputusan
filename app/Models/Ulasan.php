<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';

    protected $fillable = [
        'user_id',
        'unit_usaha_id',
        'nama_pemberi',
        'tanggal_ulasan',
        'ulasan',
    ];

    protected $casts = [
        'tanggal_ulasan' => 'date',
    ];

    // ===== RELATION =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unitUsaha()
    {
        return $this->belongsTo(UnitUsaha::class);
    }
}
