USER
 │
 │ memilih:
 │ - lokasi
 │ - jenis layanan
 │ - harga minimum/maksimum
 │ - rating minimum
 │
 ▼
PerhitunganMetodeController
 │
 ├── Filter jenis layanan
 │
 ├── Filter harga
 │
 ├── Ambil harga termurah
 │
 ├── Hitung jarak
 │       │
 │       └── HaversineService
 │
 ├── Filter rating
 │
 ▼
Matriks Keputusan
 │
 ├── Rating
 ├── Harga
 └── Jarak
 │
 ▼
SAWService
 │
 ├── Cari Max Rating
 ├── Cari Min Harga
 ├── Cari Min Jarak
 │
 ▼
Normalisasi
 │
 ├── Rating = Rating / Max Rating
 │
 ├── Harga = Min Harga / Harga
 │
 └── Jarak = Min Jarak / Jarak
 │
 ▼
Pembobotan
 │
 ├── Rating × 0.4
 ├── Harga  × 0.3
 └── Jarak  × 0.3
 │
 ▼
Penjumlahan
 │
 └── Vi = C1 + C2 + C3
 │
 ▼
Nilai Preferensi / Score
 │
 ▼
Ranking DESC
 │
 ▼
5 Laundry dengan Score Tertinggi
 │
 ▼
Response JSON
 │
 ▼
Tampilan Rekomendasi pada User