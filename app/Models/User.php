<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_layanan',
        'email',
        'username',
        'password',
        'foto',
        'role',
        'status',
        'pengawas_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Admin Laundry memiliki Pengawas.
     */
    public function pengawas()
    {
        return $this->belongsTo(
            User::class,
            'pengawas_id',
            'id'
        );
    }

    /**
     * Pengawas memiliki banyak Admin Laundry.
     */
    public function adminLaundries()
    {
        return $this->hasMany(
            User::class,
            'pengawas_id',
            'id'
        )->where('role', 'admin_laundry');
    }

    /**
     * User memiliki banyak ulasan.
     */
    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }

    /**
     * Activity log user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}
