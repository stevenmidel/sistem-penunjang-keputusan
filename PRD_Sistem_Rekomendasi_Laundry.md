# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Sistem Rekomendasi Layanan Laundry  
### Berbasis Jarak dan Ulasan Pengguna  
### Menggunakan Algoritma Haversine dan Metode SAW

---

## 1. INFORMASI UMUM

**Nama Proyek**  
Sistem Rekomendasi Layanan Laundry

**Tujuan Proyek**  
Membangun sistem yang mampu merekomendasikan layanan laundry terbaik berdasarkan:
- Jarak pengguna ke lokasi laundry
- Rating bintang
- Ulasan pengguna berbasis teks

**Studi Kasus**  
Kecamatan Biringkanaya Kota Makassar

---

## 2. LATAR BELAKANG

Pengguna sering mengalami kesulitan dalam memilih layanan laundry terbaik karena banyaknya pilihan yang tersedia. Sistem rekomendasi ini dirancang untuk membantu pengguna dalam mengambil keputusan dengan mempertimbangkan faktor jarak dan kualitas layanan berdasarkan ulasan pengguna.

---

## 3. RUANG LINGKUP SISTEM

### 3.1 Fitur Utama
- Menampilkan seluruh lokasi laundry pada peta
- Menghitung jarak laundry dari lokasi pengguna
- Mengolah ulasan teks menjadi nilai numerik
- Melakukan perangkingan laundry menggunakan metode SAW
- Menampilkan laundry terbaik berdasarkan nilai preferensi

### 3.2 Batasan Sistem
- Sistem tidak melakukan analisis sentimen berbasis machine learning
- Analisis ulasan menggunakan pendekatan kamus kata positif dan negatif
- Data lokasi dan ulasan diambil dari database internal sistem

---

## 4. DATA INPUT

### 4.1 Data Laundry
| Atribut | Tipe |
|------|------|
| Nama Laundry | String |
| Latitude | Float |
| Longitude | Float |
| Harga | Integer |
| Rating | Float |
| Ulasan | Text |

### 4.2 Data Pengguna
| Atribut | Tipe |
|------|------|
| Latitude | Float |
| Longitude | Float |

---

## 5. KRITERIA PENILAIAN

| Kriteria | Jenis |
|--------|------|
| Jarak | Cost |
| Rating | Benefit |
| Skor Ulasan | Benefit |

---

## 6. METODE PERHITUNGAN

### 6.1 Algoritma Haversine
Digunakan untuk menghitung jarak antara lokasi pengguna dan lokasi laundry berdasarkan koordinat latitude dan longitude.

**Output:**  
Jarak dalam satuan kilometer (km)

---

### 6.2 Analisis Ulasan Berbasis Kata

#### 6.2.1 Kamus Kata
- **Kata Positif:** baik, cepat, nyaman, profesional, rapih, suka, senang
- **Kata Negatif:** lama, nunggu, buruk, kecewa

#### 6.2.2 Aturan Penilaian
- Setiap kata positif bernilai +1
- Setiap kata negatif bernilai -1
- Nilai minimum adalah 0 (tidak ada skor negatif)

**Rumus Skor Ulasan:**

---

## 7. METODE SAW (SIMPLE ADDITIVE WEIGHTING)

### 7.1 Normalisasi

| Kriteria | Rumus |
|--------|------|
| Jarak | Min(Jarak) / Jarak |
| Rating | Rating / Max(Rating) |
| Ulasan | Skor Ulasan / Max(Skor Ulasan) |

---

### 7.2 Bobot Kriteria

| Kriteria | Bobot |
|--------|------|
| Jarak | 0.4 |
| Rating | 0.3 |
| Ulasan | 0.3 |
| **Total** | **1.0** |

---

### 7.3 Nilai Preferensi


---

## 8. OUTPUT SISTEM

### 8.1 Hasil Rekomendasi
- Daftar laundry diurutkan berdasarkan nilai preferensi tertinggi
- Laundry dengan nilai tertinggi direkomendasikan sebagai pilihan terbaik

### 8.2 Visualisasi
- Peta interaktif dengan marker lokasi laundry
- Informasi nama laundry ditampilkan pada marker

---

## 9. CONTOH KASUS PENGUJIAN

### 9.1 Data
| Laundry | Jarak | Rating | Skor Ulasan |
|------|------|------|------------|
| Layanan 1 | 2 km | 4.0 | 5 |
| Layanan 2 | 1 km | 5.0 | 2 |

### 9.2 Hasil
- Layanan 1 memiliki nilai preferensi lebih tinggi
- Direkomendasikan sebagai pilihan utama

---

## 10. KESIMPULAN

Metode Haversine dan SAW mampu memberikan rekomendasi yang objektif dengan menggabungkan data spasial dan kualitas layanan berdasarkan ulasan pengguna. Pendekatan ini sesuai untuk diterapkan pada sistem rekomendasi layanan laundry berbasis lokasi.

---

## 11. RENCANA PENGEMBANGAN

- Integrasi analisis sentimen berbasis machine learning
- Penambahan filter harga dan jam operasional
- Peningkatan visualisasi peta dan performa sistem

---

**Dokumen ini digunakan sebagai acuan pengembangan sistem dan dokumentasi teknis proyek.**


Alur pengembangan sistem :

User geser filter
        ↓
Frontend kirim request (AJAX / GET)
        ↓
Route user.temukan.search
        ↓
MapsAllUnitController
        ↓
Ambil data unit_usahas + ulasan
        ↓
Hitung:
- Jarak (Haversine)
- Skor ulasan (string)
- Normalisasi SAW
        ↓
Perangkingan
        ↓
Response JSON
        ↓
Frontend update:
- Marker map
- Card rekomendasi

Update 11 Mei 2026
Perubahan Pada Jenis Layanan :
1. Rubah Struktur Tabel unit_Usahas
2. install : composer require doctrine/dbal
3. Kosongkan Tabel 


| Kriteria | Jenis   | Bobot | Alasan                     |
| -------- | ------- | ----- | -------------------------- |
| Rating   | Benefit | 0.4   | Mewakili kualitas layanan  |
| Harga    | Cost    | 0.3   | Semakin murah semakin baik |
| Jarak    | Cost    | 0.3   | Kedekatan lokasi user      |


| Kriteria | Peran   | Bobot |
| -------- | ------- | ----- |
| Rating   | Benefit | 0.4   |
| Harga    | Cost    | 0.3   |
| Jarak    | Cost    | 0.3   |


Alur /Flow system:

unit_usahas + ulasan
        ↓
SentimentService (kamus_sentimen.php)
        ↓
Skor Sentimen
        ↓
HaversineService (jarak)
        ↓
SAWService (normalisasi + bobot)
        ↓
Top 5 Rekomendasi Laundry