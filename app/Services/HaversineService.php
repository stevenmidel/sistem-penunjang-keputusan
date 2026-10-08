<?php

namespace App\Services;

/**
 * ============================================================
 * CLASS HAVERSINE SERVICE
 * ============================================================
 *
 * Class ini digunakan untuk menghitung jarak antara dua titik
 * berdasarkan koordinat latitude dan longitude menggunakan
 * algoritma Haversine.
 *
 * Titik pertama  : lokasi pengguna
 * Titik kedua    : lokasi laundry
 *
 * Hasil perhitungan berupa jarak dalam satuan kilometer (km).
 */
class HaversineService
{
    /**
     * ============================================================
     * FUNGSI CALCULATE()
     * ============================================================
     *
     * Fungsi ini menerima koordinat lokasi pengguna dan koordinat
     * lokasi laundry, kemudian menghitung jarak kedua titik
     * tersebut menggunakan algoritma Haversine.
     *
     * @param float $latFrom
     * Latitude lokasi pengguna.
     *
     * @param float $lngFrom
     * Longitude lokasi pengguna.
     *
     * @param float $latTo
     * Latitude lokasi laundry.
     *
     * @param float $lngTo
     * Longitude lokasi laundry.
     *
     * @param int $earthRadius
     * Jari-jari bumi dalam kilometer.
     * Nilai yang digunakan adalah 6371 km.
     *
     * @return float
     * Jarak antara pengguna dan laundry dalam kilometer.
     */
    public function calculate(
        float $latFrom,
        float $lngFrom,
        float $latTo,
        float $lngTo,
        int $earthRadius = 6371
    ): float {

        /*
        |--------------------------------------------------------------------------
        | 1. KONVERSI DERAJAT KE RADIAN
        |--------------------------------------------------------------------------
        |
        | Koordinat latitude dan longitude yang diperoleh dari pengguna
        | dan database masih dalam satuan derajat.
        |
        | Dalam perhitungan trigonometri PHP seperti sin(), cos(), dan
        | atan2(), nilai sudut harus dinyatakan dalam radian.
        |
        | Oleh karena itu, setiap latitude dan longitude dikonversi
        | menggunakan fungsi deg2rad().
        |
        | Contoh:
        |
        | latitude pengguna
        | -5.121126318730584°
        |
        | akan dikonversi menjadi nilai radian.
        |
        |--------------------------------------------------------------------------
        */

        // Konversi latitude pengguna dari derajat menjadi radian
        $latFromRad = deg2rad($latFrom);

        // Konversi longitude pengguna dari derajat menjadi radian
        $lngFromRad = deg2rad($lngFrom);

        // Konversi latitude laundry dari derajat menjadi radian
        $latToRad = deg2rad($latTo);

        // Konversi longitude laundry dari derajat menjadi radian
        $lngToRad = deg2rad($lngTo);


        /*
        |--------------------------------------------------------------------------
        | 2. MENGHITUNG SELISIH KOORDINAT
        |--------------------------------------------------------------------------
        |
        | Setelah semua koordinat dikonversi ke radian, sistem menghitung
        | perbedaan latitude dan longitude antara lokasi pengguna dengan
        | lokasi laundry.
        |
        | Rumus:
        |
        | Δφ = φ2 - φ1
        |
        | Δλ = λ2 - λ1
        |
        | Keterangan:
        |
        | Δφ = selisih latitude
        | Δλ = selisih longitude
        | φ1 = latitude titik pertama (pengguna)
        | φ2 = latitude titik kedua (laundry)
        | λ1 = longitude titik pertama (pengguna)
        | λ2 = longitude titik kedua (laundry)
        |
        |--------------------------------------------------------------------------
        */

        // Selisih latitude antara lokasi laundry dan lokasi pengguna
        $latDelta = $latToRad - $latFromRad;

        // Selisih longitude antara lokasi laundry dan lokasi pengguna
        $lngDelta = $lngToRad - $lngFromRad;


        /*
        |--------------------------------------------------------------------------
        | 3. MENGHITUNG NILAI a PADA RUMUS HAVERSINE
        |--------------------------------------------------------------------------
        |
        | Nilai a merupakan bagian pertama dari perhitungan Haversine.
        |
        | Rumus:
        |
        | a = sin²(Δφ / 2)
        |     + cos(φ1) × cos(φ2) × sin²(Δλ / 2)
        |
        | Pada bagian ini:
        |
        | - sin() digunakan untuk menghitung sinus
        | - cos() digunakan untuk menghitung cosinus
        | - ** 2 digunakan untuk menghasilkan nilai kuadrat
        |
        | Nilai a nantinya digunakan untuk menghitung nilai c.
        |
        |--------------------------------------------------------------------------
        */

        $a =
            sin($latDelta / 2) ** 2
            +
            cos($latFromRad)
            * cos($latToRad)
            * sin($lngDelta / 2) ** 2;


        /*
        |--------------------------------------------------------------------------
        | 4. MENGHITUNG NILAI c
        |--------------------------------------------------------------------------
        |
        | Setelah nilai a diperoleh, sistem menghitung nilai sudut
        | pusat antara kedua titik bumi.
        |
        | Rumus:
        |
        | c = 2 × atan2(√a, √(1-a))
        |
        | atan2() digunakan untuk mendapatkan nilai arc tangent
        | berdasarkan dua parameter.
        |
        | sqrt() digunakan untuk menghitung akar kuadrat.
        |
        | Nilai c kemudian akan dikalikan dengan jari-jari bumi
        | untuk mendapatkan jarak sebenarnya.
        |
        |--------------------------------------------------------------------------
        */

        $c =
            2 * atan2(
                sqrt($a),
                sqrt(1 - $a)
            );


        /*
        |--------------------------------------------------------------------------
        | 5. MENGHITUNG JARAK AKHIR
        |--------------------------------------------------------------------------
        |
        | Setelah nilai c diperoleh, jarak antara kedua titik dihitung
        | menggunakan rumus:
        |
        | d = r × c
        |
        | Keterangan:
        |
        | d = jarak antara dua titik dalam kilometer
        | r = jari-jari bumi, yaitu 6371 km
        | c = nilai sudut pusat yang diperoleh dari perhitungan Haversine
        |
        | Karena $earthRadius menggunakan satuan kilometer, maka hasil
        | perhitungan juga langsung berada dalam satuan kilometer.
        |
        |--------------------------------------------------------------------------
        */

        $distance = $earthRadius * $c;


        /*
        |--------------------------------------------------------------------------
        | 6. PEMBULATAN HASIL
        |--------------------------------------------------------------------------
        |
        | Hasil jarak dapat memiliki banyak angka di belakang koma.
        | Agar lebih mudah ditampilkan dan digunakan dalam sistem,
        | hasil dibulatkan menjadi dua angka di belakang koma.
        |
        | Contoh:
        |
        | 0.723846 km
        |
        | menjadi:
        |
        | 0.72 km
        |
        |--------------------------------------------------------------------------
        */

        return round($distance, 2);
    }
}
