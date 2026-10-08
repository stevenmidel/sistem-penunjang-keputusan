<?php

namespace App\Http\Controllers\User;

// ============================================================
// IMPORT CLASS YANG DIBUTUHKAN
// ============================================================

// Controller dasar Laravel
use App\Http\Controllers\Controller;

// Model yang menyimpan data unit/usaha laundry
use App\Models\UnitUsaha;

// Service untuk menghitung jarak menggunakan algoritma Haversine
use App\Services\HaversineService;

// Service untuk melakukan perhitungan metode SAW
use App\Services\SAWService;

// Digunakan untuk menerima dan memvalidasi input dari pengguna
use Illuminate\Http\Request;

// Digunakan untuk mencatat error ke file log Laravel
use Illuminate\Support\Facades\Log;

// Model yang menyimpan daftar layanan pada setiap laundry
use App\Models\LayananLaundry;


class PerhitunganMetodeController extends Controller
{
    // ============================================================
    // PROPERTY SERVICE
    // ============================================================

    // Service Haversine digunakan untuk menghitung jarak
    // antara koordinat pengguna dengan koordinat laundry.
    protected HaversineService $haversineService;

    // Service SAW digunakan untuk menghitung nilai preferensi
    // dan menentukan ranking laundry.
    protected SAWService $sawService;


    // ============================================================
    // CONSTRUCTOR
    // ============================================================

    public function __construct(
        HaversineService $haversineService,
        SAWService $sawService
    ) {
        /*
        |--------------------------------------------------------------------------
        | INJEKSI SERVICE HAVERSINE
        |--------------------------------------------------------------------------
        |
        | Laravel akan memasukkan object HaversineService ke dalam controller.
        | Dengan demikian controller dapat memanggil:
        |
        | $this->haversineService->calculate(...)
        |
        */

        $this->haversineService = $haversineService;


        /*
        |--------------------------------------------------------------------------
        | INJEKSI SERVICE SAW
        |--------------------------------------------------------------------------
        |
        | Laravel akan memasukkan object SAWService ke dalam controller.
        | Service ini nantinya digunakan untuk menghitung nilai SAW.
        |
        */

        $this->sawService = $sawService;
    }


    // ============================================================
    // METHOD 1: NEAREST
    // ============================================================
    //
    // Fungsi:
    // Menampilkan 5 laundry TERDEKAT berdasarkan jarak.
    //
    // CATATAN:
    // Method ini TIDAK menggunakan SAW.
    //
    // Alurnya:
    //
    // Lokasi pengguna
    //       ↓
    // Validasi latitude & longitude
    //       ↓
    // Ambil seluruh laundry
    //       ↓
    // Hitung jarak dengan Haversine
    //       ↓
    // Urutkan berdasarkan jarak
    //       ↓
    // Ambil 5 laundry terdekat
    //       ↓
    // Kirim JSON ke frontend
    // ============================================================

    public function nearest(Request $request)
    {
        try {

            // ========================================================
            // 1. VALIDASI KOORDINAT PENGGUNA
            // ========================================================

            /*
            | latitude  = posisi lintang pengguna
            | longitude = posisi bujur pengguna
            |
            | Kedua nilai wajib diisi dan harus berupa angka.
            */

            $validated = $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);


            // ========================================================
            // 2. MENGAMBIL DATA LAUNDRY DARI DATABASE
            // ========================================================

            /*
            | UnitUsaha merupakan data utama laundry.
            |
            | with('layananLaundry')
            | digunakan untuk mengambil data layanan laundry
            | sekaligus melalui relasi yang sudah dibuat pada model.
            |
            | whereNotNull()
            | memastikan laundry memiliki koordinat.
            */

            $units = UnitUsaha::with('layananLaundry')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();


            // ========================================================
            // 3. CEK APAKAH DATA LAUNDRY TERSEDIA
            // ========================================================

            /*
            | Jika database tidak memiliki laundry,
            | sistem langsung mengembalikan array kosong.
            */

            if ($units->isEmpty()) {
                return response()->json([]);
            }


            // ========================================================
            // 4. MENYIAPKAN ARRAY HASIL PERHITUNGAN
            // ========================================================

            /*
            | Array $processed digunakan untuk menampung
            | seluruh data laundry yang telah diproses.
            */

            $processed = [];


            // ========================================================
            // 5. PERULANGAN UNTUK SETIAP LAUNDRY
            // ========================================================

            /*
            | Setiap laundry akan diproses satu per satu.
            */

            foreach ($units as $unit) {


                // ====================================================
                // 5.1 CEK APAKAH LAUNDRY MEMILIKI LAYANAN
                // ====================================================

                /*
                | Jika laundry tidak memiliki layanan,
                | laundry tersebut dilewati dan tidak dimasukkan
                | ke dalam rekomendasi.
                */

                if ($unit->layananLaundry->isEmpty()) {
                    continue;
                }


                // ====================================================
                // 5.2 HITUNG JARAK DENGAN ALGORITMA HAVERSINE
                // ====================================================

                /*
                | Input algoritma Haversine:
                |
                | Titik pertama:
                |   latitude pengguna
                |   longitude pengguna
                |
                | Titik kedua:
                |   latitude laundry
                |   longitude laundry
                |
                | Service Haversine kemudian:
                |
                | 1. Mengubah derajat menjadi radian
                | 2. Menghitung selisih latitude
                | 3. Menghitung selisih longitude
                | 4. Menghitung nilai a
                | 5. Menghitung nilai c
                | 6. Mengalikan dengan radius bumi
                |
                | Hasil akhirnya adalah jarak dalam kilometer.
                */

                $distance = $this->haversineService->calculate(
                    $validated['latitude'],
                    $validated['longitude'],
                    $unit->latitude,
                    $unit->longitude
                );


                // ====================================================
                // 5.3 MEMBENTUK DATA HASIL PERHITUNGAN
                // ====================================================

                /*
                | Data laundry dimasukkan ke dalam array.
                |
                | Nilai 'jarak' berasal dari hasil Haversine.
                */

                $processed[] = [
                    'id'           => $unit->id,
                    'nama_laundry' => $unit->nama_laundry,
                    'alamat'       => $unit->nama_jalan,

                    // Koordinat laundry
                    'latitude'     => (float) $unit->latitude,
                    'longitude'    => (float) $unit->longitude,

                    // Rating laundry
                    'rating'       => (float) ($unit->rating ?? 0),

                    // Jarak dari pengguna ke laundry
                    // dibulatkan menjadi 2 angka desimal
                    'jarak'        => round($distance, 2),


                    // =================================================
                    // DATA LAYANAN LAUNDRY
                    // =================================================

                    'layanan'      => $unit->layananLaundry->map(function ($layanan) {

                        return [
                            'nama'     => $layanan->nama_layanan,
                            'harga'    => (float) $layanan->harga,
                            'estimasi' => $layanan->estimasi,
                            'rating'   => (float) ($layanan->rating ?? 0),
                        ];
                    })->values()->all(),


                    // URL untuk melihat detail laundry
                    'detail_url' => route(
                        'user.laundry.detail',
                        $unit->id
                    ),
                ];
            }


            // ========================================================
            // 6. MENGURUTKAN LAUNDRY BERDASARKAN JARAK
            // ========================================================

            /*
            | Laundry dengan jarak paling kecil ditempatkan
            | pada posisi pertama.
            |
            | Contoh:
            |
            | Laundry A = 0,50 km
            | Laundry B = 1,20 km
            | Laundry C = 2,00 km
            |
            | Maka:
            |
            | A → B → C
            |
            | PERHATIAN:
            | Proses ini hanya berdasarkan Haversine.
            | SAW tidak digunakan pada method nearest().
            */

            usort(
                $processed,
                fn($a, $b) => $a['jarak'] <=> $b['jarak']
            );


            // ========================================================
            // 7. MENGAMBIL 5 LAUNDRY TERDEKAT
            // ========================================================

            /*
            | array_slice(..., 0, 5)
            | mengambil maksimal 5 data pertama setelah diurutkan.
            */

            return response()->json(
                array_slice($processed, 0, 5)
            );
        } catch (\Throwable $e) {

            // ========================================================
            // PENANGANAN ERROR
            // ========================================================

            /*
            | Jika terjadi kesalahan selama proses,
            | informasi error disimpan ke log Laravel.
            */

            Log::error('ERROR REKOMENDASI TERDEKAT', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);


            // Mengirim response error kepada frontend
            return response()->json([
                'message' =>
                'Terjadi kesalahan saat memuat laundry terdekat'
            ], 500);
        }
    }



    // ============================================================
    // METHOD 2: DAFTAR LAYANAN
    // ============================================================
    //
    // Fungsi:
    // Mengambil daftar jenis layanan yang tersedia.
    //
    // Contoh:
    // - Cuci Lipat
    // - Cuci Kering
    // - Cuci Setrika
    // - dll.
    //
    // Data ini dapat digunakan frontend sebagai pilihan/filter
    // yang dapat dipilih pengguna.
    // ============================================================

    public function daftarLayanan()
    {

        /*
        | select('nama_layanan')
        | mengambil nama layanan.
        |
        | distinct()
        | menghilangkan nama layanan yang sama.
        |
        | orderBy()
        | mengurutkan nama layanan.
        |
        | pluck()
        | mengambil hanya kolom nama_layanan.
        */

        return response()->json(

            LayananLaundry::select('nama_layanan')
                ->distinct()
                ->orderBy('nama_layanan')
                ->pluck('nama_layanan')

        );
    }



    // ============================================================
    // METHOD 3: CALCULATE
    // ============================================================
    //
    // INILAH METHOD UTAMA UNTUK REKOMENDASI SAW.
    //
    // Alur lengkap:
    //
    // Input pengguna
    //      ↓
    // Validasi input
    //      ↓
    // Ambil data laundry
    //      ↓
    // Filter jenis layanan
    //      ↓
    // Filter harga
    //      ↓
    // Ambil harga termurah
    //      ↓
    // Hitung jarak Haversine
    //      ↓
    // Filter rating
    //      ↓
    // Bentuk matriks/data keputusan
    //      ↓
    // Kirim data ke SAWService
    //      ↓
    // Normalisasi
    //      ↓
    // Pembobotan
    //      ↓
    // Hitung nilai preferensi
    //      ↓
    // Ranking
    //      ↓
    // Ambil 5 laundry terbaik
    //      ↓
    // Kirim JSON ke frontend
    // ============================================================

    public function calculate(Request $request)
    {
        try {


            // ========================================================
            // 1. VALIDASI INPUT PENGGUNA
            // ========================================================

            /*
            | latitude & longitude
            | → lokasi pengguna
            |
            | rating
            | → rating minimum yang diinginkan pengguna
            |
            | harga_min
            | → batas harga minimum
            |
            | harga_max
            | → batas harga maksimum
            |
            | jenis
            | → jenis layanan yang dipilih pengguna
            */

            $validated = $request->validate([

                'latitude'   => 'required|numeric',
                'longitude'  => 'required|numeric',

                'rating'     => 'nullable|numeric|min:1|max:5',

                'harga_min'  => 'nullable|numeric',
                'harga_max'  => 'nullable|numeric',

                'jenis'      => 'required|array|min:1',
                'jenis.*'    => 'required|string',
            ]);



            // ========================================================
            // 2. MENGAMBIL DATA SELURUH LAUNDRY
            // ========================================================

            /*
            | Mengambil laundry yang mempunyai latitude
            | dan longitude.
            |
            | with('layananLaundry')
            | mengambil layanan yang dimiliki setiap laundry.
            */

            $units = UnitUsaha::with('layananLaundry')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();


            // Jika tidak terdapat laundry
            if ($units->isEmpty()) {
                return response()->json([]);
            }



            // ========================================================
            // 3. NORMALISASI INPUT JENIS LAYANAN
            // ========================================================

            /*
            | Input pengguna dapat memiliki:
            |
            | "Cuci Lipat"
            | "cuci lipat"
            | " Cuci Lipat "
            |
            | Agar semuanya dianggap sama,
            | dilakukan:
            |
            | strtolower()
            | → mengubah menjadi huruf kecil
            |
            | trim()
            | → menghapus spasi di awal/akhir
            */

            $jenisDipilih = collect($validated['jenis'])
                ->map(fn($j) => strtolower(trim($j)))
                ->values();


            // Array untuk menyimpan alternatif laundry
            // yang lolos seluruh proses filter.
            $processed = [];



            // ========================================================
            // 4. MEMPROSES SETIAP LAUNDRY
            // ========================================================

            foreach ($units as $unit) {


                // ====================================================
                // 4.1 FILTER BERDASARKAN JENIS LAYANAN
                // ====================================================

                /*
                | Sistem memeriksa:
                |
                | Apakah laundry ini memiliki jenis layanan
                | yang dipilih pengguna?
                |
                | Contoh pengguna memilih:
                |
                | Cuci Lipat
                |
                | Maka laundry yang tidak memiliki
                | Cuci Lipat akan dikeluarkan.
                */

                $layananCocok = $unit->layananLaundry
                    ->filter(function ($layanan) use ($jenisDipilih) {

                        return $jenisDipilih->contains(
                            strtolower(
                                trim($layanan->nama_layanan)
                            )
                        );
                    });


                // Jika tidak ada layanan yang cocok,
                // laundry tidak diproses lebih lanjut.
                if ($layananCocok->isEmpty()) {
                    continue;
                }



                // ====================================================
                // 4.2 FILTER BERDASARKAN HARGA
                // ====================================================

                /*
                | Setelah jenis layanan cocok,
                | sistem memeriksa batas harga.
                |
                | Misalnya:
                |
                | harga_min = Rp5.000
                | harga_max = Rp10.000
                |
                | Maka layanan di bawah Rp5.000
                | atau di atas Rp10.000 akan dikeluarkan.
                */

                $layananCocok = $layananCocok->filter(
                    function ($layanan) use ($validated) {


                        // Jika pengguna menentukan harga minimum
                        // dan harga layanan lebih kecil dari minimum,
                        // layanan tidak digunakan.

                        if (
                            !is_null($validated['harga_min']) &&
                            $layanan->harga < $validated['harga_min']
                        ) {
                            return false;
                        }


                        // Jika pengguna menentukan harga maksimum
                        // dan harga layanan lebih besar dari maksimum,
                        // layanan tidak digunakan.

                        if (
                            !is_null($validated['harga_max']) &&
                            $layanan->harga > $validated['harga_max']
                        ) {
                            return false;
                        }


                        // Jika memenuhi seluruh batas harga,
                        // layanan tetap digunakan.

                        return true;
                    }
                );


                // Jika tidak ada layanan yang memenuhi
                // filter harga, laundry dikeluarkan.

                if ($layananCocok->isEmpty()) {
                    continue;
                }



                // ====================================================
                // 4.3 MENENTUKAN HARGA YANG DIGUNAKAN SAW
                // ====================================================

                /*
                | Satu laundry dapat memiliki beberapa layanan
                | yang dipilih pengguna.
                |
                | Contoh:
                |
                | Cuci Lipat  = Rp5.000
                | Cuci Kering = Rp7.000
                |
                | Sistem menggunakan harga TERMURAH
                | sebagai nilai kriteria harga SAW.
                |
                | Sehingga:
                |
                | hargaSAW = Rp5.000
                */

                $hargaSAW = $layananCocok->min('harga');



                // ====================================================
                // 4.4 MENGHITUNG JARAK DENGAN HAVERSINE
                // ====================================================

                /*
                | Setelah laundry lolos filter layanan dan harga,
                | sistem menghitung jarak antara:
                |
                | Lokasi pengguna
                |        dengan
                | Lokasi laundry
                |
                | Hasilnya menjadi nilai kriteria:
                |
                | C3 = Jarak
                |
                | Dalam SAW, jarak merupakan kriteria COST.
                */

                $distance = $this->haversineService->calculate(

                    // Koordinat pengguna
                    $validated['latitude'],
                    $validated['longitude'],

                    // Koordinat laundry
                    $unit->latitude,
                    $unit->longitude
                );



                // ====================================================
                // 4.5 FILTER BERDASARKAN RATING
                // ====================================================

                /*
                | Jika pengguna menentukan rating minimum,
                | sistem akan membuang laundry yang ratingnya
                | berada di bawah nilai tersebut.
                |
                | Contoh:
                |
                | Pengguna memilih rating minimum 4.0.
                |
                | Laundry dengan rating:
                |
                | 4.5 → dipertahankan
                | 4.0 → dipertahankan
                | 3.9 → dikeluarkan
                */

                if (
                    !is_null($validated['rating']) &&
                    ($unit->rating ?? 0) < $validated['rating']
                ) {
                    continue;
                }



                // ====================================================
                // 4.6 MEMBENTUK DATA UNTUK PERHITUNGAN SAW
                // ====================================================

                /*
                | Pada bagian ini sistem membentuk data alternatif
                | yang akan menjadi dasar matriks keputusan SAW.
                |
                | Tiga nilai utama yang digunakan SAW adalah:
                |
                | C1 = rating
                | C2 = harga
                | C3 = jarak
                |
                | Contoh:
                |
                | JD Laundry
                | rating = 5.0
                | harga  = 5000
                | jarak  = 0.08 km
                */

                $processed[] = [

                    // Identitas laundry
                    'id'            => $unit->id,
                    'nama_laundry'  => $unit->nama_laundry,
                    'alamat'        => $unit->nama_jalan,

                    // Koordinat laundry
                    'latitude'      => (float) $unit->latitude,
                    'longitude'     => (float) $unit->longitude,

                    // =================================================
                    // C1 = RATING
                    // =================================================
                    //
                    // Rating digunakan sebagai kriteria BENEFIT.
                    //
                    // Semakin tinggi rating,
                    // semakin baik nilai alternatif.
                    //
                    // Bobot C1 = 0.4

                    'rating'        => (float) ($unit->rating ?? 0),


                    // =================================================
                    // C2 = HARGA
                    // =================================================
                    //
                    // Harga yang digunakan adalah harga termurah
                    // dari layanan yang dipilih pengguna.
                    //
                    // Harga merupakan kriteria COST.
                    //
                    // Semakin murah semakin baik.
                    //
                    // Bobot C2 = 0.3

                    'harga'         => (float) $hargaSAW,


                    // =================================================
                    // C3 = JARAK
                    // =================================================
                    //
                    // Jarak berasal dari algoritma Haversine.
                    //
                    // Jarak merupakan kriteria COST.
                    //
                    // Semakin dekat semakin baik.
                    //
                    // Bobot C3 = 0.3

                    'jarak'         => round($distance, 2),



                    // =================================================
                    // INFORMASI LAYANAN
                    // =================================================

                    'layanan'       => $layananCocok
                        ->map(function ($layanan) {

                            return [

                                'nama'      => $layanan->nama_layanan,

                                'harga'     => (float) $layanan->harga,

                                'estimasi'  => $layanan->estimasi,

                                'rating'    => (float) (
                                    $layanan->rating ?? 0
                                ),

                            ];
                        })
                        ->values()
                        ->all(),


                    // URL detail laundry
                    'detail_url' => route(
                        'user.laundry.detail',
                        $unit->id
                    ),
                ];
            }



            // ========================================================
            // 5. CEK HASIL SELEKSI
            // ========================================================

            /*
            | Jika semua laundry gugur karena filter,
            | maka tidak ada alternatif untuk dihitung SAW.
            */

            if (empty($processed)) {
                return response()->json([]);
            }



            // ========================================================
            // 6. PERHITUNGAN METODE SAW
            // ========================================================

            /*
            | Data yang sudah terkumpul dikirim ke SAWService.
            |
            | SAWService akan:
            |
            | 1. Menentukan rating maksimum
            | 2. Menentukan harga minimum
            | 3. Menentukan jarak minimum
            | 4. Melakukan normalisasi
            | 5. Mengalikan hasil normalisasi dengan bobot
            | 6. Menjumlahkan seluruh nilai
            | 7. Menghasilkan nilai preferensi
            | 8. Mengurutkan alternatif berdasarkan score
            |
            | Rumus:
            |
            | Vi =
            | (0.4 × rating/maxRating)
            | +
            | (0.3 × minHarga/harga)
            | +
            | (0.3 × minJarak/jarak)
            |
            */

            $ranking = $this->sawService->calculate($processed);



            // ========================================================
            // 7. MENGAMBIL 5 HASIL RANKING TERATAS
            // ========================================================

            /*
            | SAWService sudah mengurutkan data
            | berdasarkan score terbesar.
            |
            | values()
            | → mengatur kembali index array.
            |
            | take(5)
            | → mengambil 5 laundry dengan score tertinggi.
            */

            return response()->json(

                collect($ranking)
                    ->values()
                    ->take(5)

            );
        } catch (\Throwable $e) {


            // ========================================================
            // PENANGANAN ERROR
            // ========================================================

            /*
            | Jika terjadi error pada proses rekomendasi,
            | error dicatat ke log Laravel.
            */

            Log::error('ERROR REKOMENDASI LAUNDRY', [

                'message' => $e->getMessage(),

                'line'    => $e->getLine(),

                'file'    => $e->getFile(),

            ]);


            // Mengirim response error ke frontend.

            return response()->json([
                'message' =>
                'Terjadi kesalahan saat memproses rekomendasi'
            ], 500);
        }
    }
}
