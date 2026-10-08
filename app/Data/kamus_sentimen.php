<?php

/**
 * Kamus Sentimen Laundry
 * Digunakan oleh SentimentService
 * 
 * Skema:
 * - positif : kata / frasa bernilai baik
 * - negatif : kata / frasa bernilai buruk
 * - netral  : opsional (keterangan informatif)
 */

return [

    'positif' => [
        // kualitas hasil
        'bersih',
        'wangi',
        'rapi',
        'tidak rusak',
        'hasil bagus',
        'hasil memuaskan',

        // pelayanan
        'ramah',
        'pelayanan baik',
        'pelayanan ramah',
        'cepat',
        'tepat waktu',
        'profesional',
        'responsif',

        // harga & nilai
        'murah',
        'harga terjangkau',
        'harga sesuai',
        'worth it',

        // rekomendasi
        'recommended',
        'sangat direkomendasikan',
        'mantap',
        'puas',
        'sangat puas'
    ],

    'negatif' => [
        // kualitas hasil
        'kotor',
        'bau',
        'tidak wangi',
        'kusut',
        'rusak',
        'hilang',

        // pelayanan
        'lama',
        'sangat lama',
        'pelayanan buruk',
        'tidak ramah',
        'tidak profesional',
        'kecewa',

        // harga
        'mahal',
        'harga tidak sesuai',

        // keluhan umum
        'tidak puas',
        'mengecewakan',
        'parah',
        'buruk'
    ],

    /**
     * Netral bersifat opsional
     * Biasanya tidak mempengaruhi skor
     */
    'netral' => [
        'cukup',
        'lumayan',
        'biasa saja',
        'standar',
        'ok'
    ]
];
