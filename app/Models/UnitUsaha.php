<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LayananLaundry;

class UnitUsaha extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_laundry',
        'foto_laundry',
        'nama_jalan',
        'latitude',
        'longitude',
        'weekday_open',
        'weekday_close',
        'weekend_open',
        'weekend_close',
        'keterangan',
        'rating',
        'admin_id'
    ];

    protected $casts = [
        // 'jenis_layanan' => 'array',  ← DIHAPUS, konflik dengan accessor di atas
        'rating' => 'float',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function getJamOperasionalAttribute(): string
    {
        return
            "Senin–Jumat: {$this->weekday_open} – {$this->weekday_close}\n" .
            "Sabtu–Minggu: {$this->weekend_open} – {$this->weekend_close}";
    }

    /**
     * Satu Unit Usaha memiliki banyak layanan
     */
    public function layanan()
    {
        return $this->hasMany(LayananLaundry::class);
    }

    public function layananLaundry()
    {
        return $this->hasMany(LayananLaundry::class, 'unit_usaha_id');
    }
}
