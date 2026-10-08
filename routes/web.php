<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLaundryAuthController;
use App\Http\Controllers\Auth\SuperAdminAuthController;
use App\Http\Controllers\AdminLaundry\DashboardController;
use App\Http\Controllers\AdminLaundryUnitController;
use App\Http\Controllers\SuperAdminLaundryController;
use App\Http\Controllers\SuperAdmin\UlasanController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\LaundryController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\User\MapsAllUnitController;
use App\Http\Controllers\User\PerhitunganMetodeController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\SuperAdmin\PengawasLaundryController;
use App\Http\Controllers\Pengawas\PengawasController;
use App\Http\Controllers\Auth\PengawasAuthController;
use App\Http\Controllers\PengawasActivityLogController;
use App\Http\Controllers\Auth\UnifiedAuthController;
use App\Http\Controllers\Auth\PasswordResetController;


/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('user.home');

Route::get(
    '/map',
    fn() =>
    view('pages.user.map')
)->name('user.map');

Route::get(
    '/about',
    fn() =>
    view('pages.user.about')
)->name('user.about');

// Route Detail Temukan untuk memuat laundry saat pertama kali diakses
Route::get('/laundry/terdekat', [PerhitunganMetodeController::class, 'nearest'])
    ->name('user.laundry.terdekat');

Route::get(
    '/layanan/list',
    [PerhitunganMetodeController::class, 'daftarLayanan']
)->name('user.layanan.list');

Route::post('/visitor/register', [VisitorController::class, 'store'])
    ->name('visitor.register');

Route::get('/laundry/{unitUsaha}', [LaundryController::class, 'show'])
    ->name('user.laundry.detail');

Route::get('/temukan-laundry', function () {
    return view('pages.user.detail.DetailTemukan');
})->name('user.temukan');

Route::get('/keunggulan', function () {
    return view('pages.user.detail.DetailFeatures');
})->name('user.features');

Route::get('/partner-laundry', [HomeController::class, 'partner'])
    ->name('user.partner');

Route::get('/maps/laundry', [MapsAllUnitController::class, 'index'])
    ->name('user.maps.laundry');

Route::post(
    '/temukan-laundry/rekomendasi',
    [PerhitunganMetodeController::class, 'calculate']
)->name('user.temukan.rekomendasi');


/*
|--------------------------------------------------------------------------
| UNIFIED LOGIN ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login', [UnifiedAuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [UnifiedAuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.process');

Route::post('/logout', [UnifiedAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN LAUNDRY ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin-laundry')
    ->name('admin.laundry.')
    ->group(function () {

        /*
        | AUTH (GUEST)
        */
        Route::get('/login', fn() => redirect()->route('login'))
            ->name('login');

        Route::get(
            '/register',
            fn() =>
            view('pages.AdminLaundry.RegisterAdmin')
        )->name('register');



        Route::post('/register', [AdminLaundryAuthController::class, 'register'])
            ->name('register.process');

        /*
        | AUTHENTICATED
        */
        Route::middleware(['auth', 'role:admin_laundry'])->group(function () {


            // Logout
            Route::post('/logout', [UnifiedAuthController::class, 'logout'])
                ->name('logout');


            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            // Profile
            Route::get(
                '/profile',
                fn() =>
                view('pages.AdminLaundry.profileuser')
            )->name('profile');

            Route::post('/profile', [AdminLaundryAuthController::class, 'updateProfile'])
                ->name('profile.update');

            /*
            | UNIT USAHA
            */
            Route::get('/unit-usaha', [AdminLaundryUnitController::class, 'index'])
                ->name('unit.index');

            Route::get(
                '/unit-usaha/tambah',
                fn() =>
                view('pages.AdminLaundry.TambahUnit')
            )->name('unit.tambah');

            Route::post('/unit-usaha/tambah', [AdminLaundryUnitController::class, 'store'])
                ->name('unit.store');

            Route::get('/unit-usaha/edit/{id}', [AdminLaundryUnitController::class, 'edit'])
                ->name('unit.edit');

            Route::put('/unit-usaha/edit/{id}', [AdminLaundryUnitController::class, 'update'])
                ->name('unit.update');

            Route::delete('/unit-usaha/{id}', [AdminLaundryUnitController::class, 'destroy'])
                ->name('unit.destroy');
        });
    });


/*
|--------------------------------------------------------------------------
| SUPER ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('superadmin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | AUTH (GUEST)
        |--------------------------------------------------------------------------
        */

        Route::get('/login', fn() => redirect()->route('login'))
            ->name('login');


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATED
        |--------------------------------------------------------------------------
        */

        Route::middleware(['auth', 'role:super_admin'])->group(function () {

            // =====================================================
            // LOGOUT
            // =====================================================

            Route::post(
                '/logout',
                [SuperAdminAuthController::class, 'logout']
            )->name('logout');


            // =====================================================
            // DASHBOARD
            // =====================================================

            Route::get(
                '/dashboard',
                [SuperAdminDashboardController::class, 'index']
            )->name('dashboard');


            // =====================================================
            // PROFILE
            // =====================================================

            Route::get(
                '/profile',
                [SuperAdminAuthController::class, 'showProfile']
            )->name('profile');

            Route::put(
                '/profile',
                [SuperAdminAuthController::class, 'updateProfile']
            )->name('profile.update');


            // =====================================================
            // LAUNDRY
            // =====================================================

            Route::get(
                '/laundry',
                [SuperAdminLaundryController::class, 'index']
            )->name('laundry');

            Route::get(
                '/laundry/{id}',
                [SuperAdminLaundryController::class, 'show']
            )->name('laundry.detail');

            Route::post(
                '/laundry/{id}/rating',
                [SuperAdminLaundryController::class, 'storeRating']
            )->name('laundry.rating');

            Route::post(
                '/laundry/{id}/ulasan',
                [UlasanController::class, 'store']
            )->name('laundry.ulasan');


            // =====================================================
            // ADMIN LAUNDRY
            // =====================================================

            Route::get(
                '/admin-laundry',
                [SuperAdminAuthController::class, 'index']
            )->name('admin-laundry.index');

            Route::put(
                '/admin-laundry/{id}/status',
                [SuperAdminAuthController::class, 'updateStatus']
            )->name('admin-laundry.update-status');

            // =====================================================
            // PENGAWAS LAUNDRY
            // =====================================================



            // Halaman daftar Pengawas Laundry
            Route::get(
                '/pengawas',
                [SuperAdminAuthController::class, 'indexPengawas']
            )->name('pengawas.index');

            // Tambah Pengawas Laundry
            Route::post(
                '/pengawas',
                [SuperAdminAuthController::class, 'storePengawas']
            )->name('pengawas.store');

            // Update status Pengawas Laundry
            Route::put(
                '/pengawas/{id}/status',
                [SuperAdminAuthController::class, 'updatePengawasStatus']
            )->name('pengawas.update-status');

            // Tetapkan Pengawas ke Admin Laundry
            Route::put(
                '/admin-laundry/{id}/pengawas',
                [SuperAdminAuthController::class, 'assignPengawas']
            )->name('admin-laundry.assign-pengawas');

            // Lepaskan Pengawas dari Admin Laundry
            Route::delete(
                '/admin-laundry/{id}/pengawas',
                [SuperAdminAuthController::class, 'removePengawas']
            )->name('admin-laundry.remove-pengawas');

            // =====================================================
            // RATING LAYANAN
            // =====================================================

            Route::put(
                '/layanan/{id}/rating',
                [SuperAdminLaundryController::class, 'updateRatingLayanan']
            )->name('layanan.rating');
        });
    });

/*
|--------------------------------------------------------------------------
| PENGAWAS LAUNDRY ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('pengawas')
    ->name('pengawas.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | AUTH
        |--------------------------------------------------------------------------
        */

        Route::get('/login', fn() => redirect()->route('login'))
            ->name('login');


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATED
        |--------------------------------------------------------------------------
        */

        Route::middleware(['auth', 'role:pengawas_laundry'])->group(function () {

            Route::post('/logout', [UnifiedAuthController::class, 'logout'])
                ->name('logout');

            Route::get(
                '/dashboard',
                [PengawasController::class, 'index']
            )->name('dashboard');

            Route::get(
                '/activity-log',
                [PengawasActivityLogController::class, 'index']
            )->name('activity-log');
        });
    });


// =====================================================
// LUPA PASSWORD
// =====================================================

Route::get(
    '/lupa-password',
    [PasswordResetController::class, 'showRequestForm']
)->name('password.request');

Route::post(
    '/lupa-password',
    [PasswordResetController::class, 'sendOtp']
)->name('password.email');

Route::get(
    '/verifikasi-password',
    [PasswordResetController::class, 'showVerifyForm']
)->name('password.verify');

Route::post(
    '/verifikasi-password',
    [PasswordResetController::class, 'verifyOtp']
)->name('password.verify.process');

Route::get(
    '/reset-password',
    [PasswordResetController::class, 'showResetForm']
)->name('password.reset');

Route::post(
    '/reset-password',
    [PasswordResetController::class, 'resetPassword']
)->name('password.update');
