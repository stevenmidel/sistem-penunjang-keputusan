<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;

class MapsAllUnitController extends Controller
{
    /**
     * Ambil semua titik lokasi laundry
     */
    public function index()
    {
        return response()->json(
            UnitUsaha::select(
                'id',
                'nama_laundry',
                'latitude',
                'longitude'
            )
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get()
        );
    }
}
