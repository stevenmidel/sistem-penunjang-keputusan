<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LayananLaundry extends Model
{
    use HasFactory;

    protected $table = 'layanan_laundry';

    protected $fillable = [
        'unit_usaha_id',
        'nama_layanan',
        'harga',
        'estimasi',
        'deskripsi',
        'rating',
    ];

    // Casting 
    protected $casts = [
        'harga' => 'integer',
        'rating' => 'float',
    ];

    /**
     * Relasi ke Unit Usaha
     */
    public function unitUsaha()
    {
        return $this->belongsTo(UnitUsaha::class);
    }
}
