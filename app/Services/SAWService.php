<?php

namespace App\Services;

/**
 * ============================================================
 * CLASS SAW SERVICE
 * ============================================================
 *
 * Class ini digunakan untuk melakukan proses perhitungan
 * rekomendasi menggunakan metode Simple Additive Weighting
 * (SAW).
 *
 * Kriteria yang digunakan dalam sistem:
 *
 * C1 = Rating  → Benefit
 * C2 = Harga   → Cost
 * C3 = Jarak   → Cost
 *
 * Bobot masing-masing kriteria:
 *
 * W1 = 0.4 atau 40% untuk Rating
 * W2 = 0.3 atau 30% untuk Harga
 * W3 = 0.3 atau 30% untuk Jarak
 *
 * Secara umum proses SAW pada sistem ini adalah:
 *
 * 1. Menerima data alternatif laundry.
 * 2. Menentukan nilai maksimum rating.
 * 3. Menentukan nilai minimum harga.
 * 4. Menentukan nilai minimum jarak.
 * 5. Melakukan normalisasi setiap kriteria.
 * 6. Mengalikan hasil normalisasi dengan bobot.
 * 7. Menjumlahkan seluruh nilai terbobot.
 * 8. Menghasilkan nilai preferensi (score).
 * 9. Mengurutkan laundry berdasarkan score tertinggi.
 */
class SAWService
{
    /**
     * ============================================================
     * FUNGSI CALCULATE()
     * ============================================================
     *
     * Fungsi ini menerima seluruh data laundry yang telah
     * diproses sebelumnya oleh PerhitunganMetodeController.
     *
     * Data yang diterima minimal memiliki:
     *
     * - rating
     * - harga
     * - jarak
     *
     * Kemudian data tersebut dihitung menggunakan metode SAW
     * untuk mendapatkan nilai preferensi setiap laundry.
     *
     * @param array $data
     * Data alternatif laundry yang akan dihitung.
     *
     * @return array
     * Data laundry yang sudah memiliki nilai score dan
     * telah diurutkan berdasarkan score tertinggi.
     */
    public function calculate(array $data): array
    {

        /*
        |--------------------------------------------------------------------------
        | 1. MENENTUKAN NILAI MAKSIMUM RATING
        |--------------------------------------------------------------------------
        |
        | Rating merupakan kriteria BENEFIT.
        |
        | Artinya:
        |
        | Semakin tinggi nilai rating, semakin baik alternatif tersebut.
        |
        | Dalam metode SAW untuk kriteria benefit digunakan rumus:
        |
        | Rij = Xij / Max(Xij)
        |
        | Oleh karena itu sistem terlebih dahulu mencari rating
        | tertinggi dari seluruh alternatif.
        |
        | Contoh:
        |
        | Rating:
        | 4.6
        | 5.0
        | 4.0
        | 4.6
        | 3.3
        |
        | Maka:
        |
        | Max Rating = 5.0
        |
        |--------------------------------------------------------------------------
        */

        $maxRating = max(
            array_column($data, 'rating')
        ) ?: 1;


        /*
        |--------------------------------------------------------------------------
        | 2. MENENTUKAN NILAI MINIMUM HARGA
        |--------------------------------------------------------------------------
        |
        | Harga merupakan kriteria COST.
        |
        | Artinya:
        |
        | Semakin rendah harga, semakin baik alternatif tersebut.
        |
        | Dalam metode SAW untuk kriteria cost digunakan rumus:
        |
        | Rij = Min(Xij) / Xij
        |
        | Oleh karena itu sistem mencari harga paling murah
        | dari seluruh alternatif.
        |
        | Contoh:
        |
        | Harga:
        | 5000
        | 6000
        | 6000
        | 7000
        |
        | Maka:
        |
        | Min Harga = 5000
        |
        |--------------------------------------------------------------------------
        */

        $minHarga = min(
            array_filter(
                array_column($data, 'harga')
            )
        ) ?: 1;


        /*
        |--------------------------------------------------------------------------
        | 3. MENENTUKAN NILAI MINIMUM JARAK
        |--------------------------------------------------------------------------
        |
        | Jarak juga merupakan kriteria COST.
        |
        | Artinya:
        |
        | Semakin dekat lokasi laundry dengan pengguna,
        | semakin baik alternatif tersebut.
        |
        | Dalam metode SAW untuk kriteria cost digunakan:
        |
        | Rij = Min(Xij) / Xij
        |
        | Oleh karena itu sistem mencari jarak terpendek
        | dari seluruh alternatif.
        |
        | Nilai jarak tersebut sebelumnya telah diperoleh
        | melalui algoritma Haversine.
        |
        | Contoh:
        |
        | Jarak:
        | 0.08 km
        | 0.50 km
        | 0.60 km
        | 0.90 km
        |
        | Maka:
        |
        | Min Jarak = 0.08 km
        |
        |--------------------------------------------------------------------------
        */

        $minJarak = min(
            array_filter(
                array_column($data, 'jarak')
            )
        ) ?: 1;


        /*
        |--------------------------------------------------------------------------
        | 4. MELAKUKAN PERHITUNGAN SETIAP ALTERNATIF
        |--------------------------------------------------------------------------
        |
        | foreach digunakan untuk memproses setiap laundry
        | yang terdapat dalam matriks keputusan.
        |
        | Contoh alternatif:
        |
        | A1 = Arkana Laundry
        | A2 = Puncak Laundry
        | A3 = Ponggawa Laundry
        | A4 = Nay Laundry BTP
        | ...
        |
        | Setiap alternatif akan mendapatkan nilai:
        |
        | - Normalisasi rating
        | - Normalisasi harga
        | - Normalisasi jarak
        | - Nilai akhir/score
        |
        |--------------------------------------------------------------------------
        */

        foreach ($data as &$item) {


            /*
            |--------------------------------------------------------------------------
            | 5. MENANGANI NILAI HARGA
            |--------------------------------------------------------------------------
            |
            | Sistem memeriksa apakah harga lebih besar dari 0.
            |
            | Jika harga > 0:
            |     gunakan harga sebenarnya.
            |
            | Jika harga = 0:
            |     gunakan nilai minimum harga.
            |
            | Hal ini dilakukan agar proses pembagian dalam
            | normalisasi tidak menghasilkan nilai yang tidak valid.
            |
            |--------------------------------------------------------------------------
            */

            $harga = $item['harga'] > 0
                ? $item['harga']
                : $minHarga;


            /*
            |--------------------------------------------------------------------------
            | 6. MENANGANI NILAI JARAK
            |--------------------------------------------------------------------------
            |
            | Sistem memberikan batas minimum jarak sebesar 0.5 km.
            |
            | Artinya:
            |
            | Jika jarak alternatif kurang dari 0.5 km,
            | maka nilai yang digunakan dalam perhitungan
            | SAW adalah 0.5 km.
            |
            | Contoh:
            |
            | Jarak Haversine = 0.08 km
            |
            | Maka:
            |
            | max(0.08, 0.5) = 0.5
            |
            | Tujuannya adalah mencegah nilai pembagi terlalu kecil
            | yang dapat menghasilkan nilai normalisasi jarak
            | yang sangat besar dan membuat kriteria jarak
            | terlalu dominan.
            |
            |--------------------------------------------------------------------------
            */

            $jarak = max(
                $item['jarak'],
                0.5
            );


            /*
            |--------------------------------------------------------------------------
            | 7. MENGHITUNG NILAI PREFERENSI / SCORE
            |--------------------------------------------------------------------------
            |
            | Bagian ini merupakan inti dari metode SAW.
            |
            | Rumus yang digunakan sistem:
            |
            | Vi =
            | (0.4 × Rating / Max Rating)
            | +
            | (0.3 × Min Harga / Harga)
            | +
            | (0.3 × Min Jarak / Jarak)
            |
            | Keterangan:
            |
            | 0.4 = bobot rating
            | 0.3 = bobot harga
            | 0.3 = bobot jarak
            |
            | Rating:
            | -----------------------------
            | Rating / Max Rating
            |
            | Karena rating merupakan BENEFIT.
            |
            | Harga:
            | -----------------------------
            | Min Harga / Harga
            |
            | Karena harga merupakan COST.
            |
            | Jarak:
            | -----------------------------
            | Min Jarak / Jarak
            |
            | Karena jarak merupakan COST.
            |
            | Setelah setiap nilai normalisasi diperoleh,
            | masing-masing dikalikan dengan bobotnya.
            |
            | Kemudian seluruh nilai tersebut dijumlahkan.
            |
            | Hasil akhirnya disebut:
            |
            | SCORE / NILAI PREFERENSI (Vi)
            |
            |--------------------------------------------------------------------------
            */

            $item['score'] =

                /*
                |--------------------------------------------------------------
                | Kriteria C1 = Rating
                | Jenis     = Benefit
                | Bobot     = 0.4
                |
                | Normalisasi:
                |
                | Rating / Max Rating
                |
                | Kemudian dikalikan bobot 0.4.
                |--------------------------------------------------------------
                */

                (0.4 * (
                    $item['rating'] / $maxRating
                ))

                +

                /*
                |--------------------------------------------------------------
                | Kriteria C2 = Harga
                | Jenis     = Cost
                | Bobot     = 0.3
                |
                | Normalisasi:
                |
                | Min Harga / Harga
                |
                | Kemudian dikalikan bobot 0.3.
                |--------------------------------------------------------------
                */

                (0.3 * (
                    $minHarga / $harga
                ))

                +

                /*
                |--------------------------------------------------------------
                | Kriteria C3 = Jarak
                | Jenis     = Cost
                | Bobot     = 0.3
                |
                | Normalisasi:
                |
                | Min Jarak / Jarak
                |
                | Kemudian dikalikan bobot 0.3.
                |--------------------------------------------------------------
                */

                (0.3 * (
                    $minJarak / $jarak
                ));
        }


        /*
        |--------------------------------------------------------------------------
        | 8. MENGURUTKAN HASIL BERDASARKAN SCORE
        |--------------------------------------------------------------------------
        |
        | Setelah seluruh alternatif mendapatkan nilai score,
        | sistem melakukan pengurutan berdasarkan nilai score.
        |
        | Operator:
        |
        | $b['score'] <=> $a['score']
        |
        | digunakan untuk mengurutkan secara DESCENDING.
        |
        | Artinya:
        |
        | Score terbesar → peringkat pertama
        | Score berikutnya → peringkat kedua
        | Score lebih kecil → peringkat berikutnya
        |
        | Semakin tinggi nilai score, semakin direkomendasikan
        | alternatif tersebut.
        |
        |--------------------------------------------------------------------------
        */

        usort(
            $data,
            fn($a, $b) =>
            $b['score'] <=> $a['score']
        );


        /*
        |--------------------------------------------------------------------------
        | 9. MENGEMBALIKAN HASIL PERHITUNGAN
        |--------------------------------------------------------------------------
        |
        | Setelah data selesai dihitung dan diurutkan,
        | hasil dikembalikan ke PerhitunganMetodeController.
        |
        | Controller kemudian mengambil hasil tersebut
        | dan mengirimkannya sebagai response JSON kepada
        | halaman/frontend.
        |
        |--------------------------------------------------------------------------
        */

        return $data;
    }
}
