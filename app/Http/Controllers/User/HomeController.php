<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use App\Models\Visitor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Tracking Visitor
        |--------------------------------------------------------------------------
        */

        $token = $request->cookie('visitor_token');

        if ($token) {

            $visitor = Visitor::where('visitor_token', $token)->first();

            if ($visitor) {

                // Tambah kunjungan jika sudah lewat 15 menit
                if ($visitor->last_visit->diffInMinutes(now()) >= 15) {

                    $visitor->update([
                        'visit_count' => $visitor->visit_count + 1,
                        'last_visit'  => now(),
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Data Laundry
        |--------------------------------------------------------------------------
        */

        $laundries = UnitUsaha::select(
            'id',
            'nama_laundry',
            'foto_laundry',
            'nama_jalan',
            'rating'
        )
            ->orderByDesc('rating')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Top Visitor
        |--------------------------------------------------------------------------
        */

        $topVisitors = Visitor::orderByDesc('visit_count')
            ->orderByDesc('last_visit')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Total Visitor
        |--------------------------------------------------------------------------
        */

        $totalVisitors = Visitor::count();

        /*
        |--------------------------------------------------------------------------
        | Total Partner Laundry
        |--------------------------------------------------------------------------
        */

        $totalPartners = UnitUsaha::count();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'pages.user.landing.index',
            compact(
                'laundries',
                'topVisitors',
                'totalVisitors',
                'totalPartners'
            )
        );
    }

    public function partner()
    {
        $laundries = UnitUsaha::select(
            'id',
            'nama_laundry',
            'foto_laundry',
            'nama_jalan',
            'rating'
        )
            ->orderByDesc('rating')
            ->get();

        return view(
            'pages.user.detail.DetailPartner',
            compact('laundries')
        );
    }
}
