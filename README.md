==================================================================================
IDENTITAS KELOMPOK
==================================================================================
Nomor Kelompok  : Kelompok F
Judul Project   : Pengembangan Sistem Manajemen Persuratan (SIMAS-FTMM) & Dashboard Analisis Data (OLAP)

Anggota Kelompok:
1. [RATU APHRODITE CINTA AURORA]     - [164231009]
2. [ARIO RIZKY MUHAMMAD]             - [164231080]
3. [OKAN ATHALLAH MAREDITH]          - [164231088]
4. [BUNGA AMANDA AURORA]             - [164231098]
5. [ATHALIA ANDRIA LOLY ARUAN]       - [164231110]

==================================================================================
DESKRIPSI SINGKAT PROJECT
==================================================================================
Project ini bertujuan untuk mendigitalkan proses pengajuan surat kemahasiswaan di 
lingkungan FTMM Universitas Airlangga. Sistem ini mengintegrasikan dua modul utama:

1. Modul Operasional (Laravel): Memungkinkan mahasiswa mengajukan surat dan pihak 
   terkait (BEM, Admin, Dekanat) melakukan approval/tracking secara real-time.
2. Modul Analisis (Pentaho & Dashboard): Menggunakan proses ETL untuk membentuk 
   Data Warehouse yang divisualisasikan menjadi grafik tren dan performa layanan.

==================================================================================
STRUKTUR FILE (DALAM ZIP)
==================================================================================
1. Folder "Laporan"
   - Berisi dokumen Laporan Akhir Project (PDF).

2. Folder "Codingan Laravel"
   - Source code aplikasi web (Controller, Model, View, dll).
   - Siap dijalankan menggunakan local server (Artisan).

3. Folder "Data Pentaho"
   - Database Master (.sql): File dump database `persuratan.sql`.
   - ETL Files (.ktr): 6 File transformasi Pentaho untuk mengisi Data Warehouse.

==================================================================================
SPESIFIKASI TEKNOLOGI
==================================================================================
- Backend Framework : Laravel (PHP 8.x)
- Database          : MySQL
- ETL Tool          : Pentaho Data Integration (Spoon)
- Frontend          : Blade, Bootstrap, Custom CSS
- Visualisasi Data  : Chart.js

==================================================================================
TATA CARA PENGGUNAAN (HOW TO RUN)
==================================================================================

[LANGKAH 1: SETUP DATABASE]
1. Buka phpMyAdmin atau SQL Client.
2. Buat database baru dengan nama: persuratan
3. Buka folder "Data Pentaho", lalu import file `persuratan.sql` ke database tersebut.

[LANGKAH 2: SETUP APLIKASI LARAVEL]
1. Buka terminal/CMD, arahkan ke direktori folder "Codingan Laravel".
2. Install dependensi (jika folder /vendor belum ada):
   > composer install
3. Salin file environment:
   > cp .env.example .env
4. Buka file `.env`, pastikan konfigurasi database sesuai:
   DB_DATABASE=persuratan
   DB_USERNAME=root
   DB_PASSWORD=  (kosongkan jika default, atau sesuaikan dengan password local Anda)
5. Generate Application Key:
   > php artisan key:generate
6. Jalankan server:
   > php artisan serve
7. Aplikasi dapat diakses di: http://127.0.0.1:8000

[LANGKAH 3: MENJALANKAN PROSES ETL (PENTAHO)]
*Penting: Jalankan file transformasi secara berurutan agar relasi data terbentuk benar.*

Buka aplikasi Pentaho Data Integration (Spoon), lalu buka dan jalankan file yang ada 
di folder "Data Pentaho" dengan urutan sebagai berikut:

1. Load Dimensi (Master Data):
   - Run `01_dim_mahasiswa.ktr`
   - Run `02_dim_waktu.ktr`
   - Run `03_dims_lain.ktr`

2. Load Fakta (Transaksi):
   - Run `04_fact_pengajuan.ktr`
   - Run `05_fact_durasi.ktr`
   - Run `06_fact_approval.ktr`

*Catatan: Pastikan koneksi database di Pentaho ("koneksi_laravel") sudah disesuaikan 
dengan settingan MySQL lokal Anda (Username/Password).*

==================================================================================
AKUN LOGIN (CREDENTIALS)
==================================================================================
Gunakan akun berikut untuk menguji fitur sistem:

A. ROLE MAHASISWA (Pengaju Surat)
   - Username : 164231088
   - Password : okan

B. ROLE ADMIN / APPROVER (Dashboard & Approval)
   Password untuk semua akun di bawah ini adalah: 12345

   1. BEM           : bem01
   2. Akademik      : akd01
   3. Sekretariat   : sek01
   4. Dekan         : dek01
   5. Wakil Dekan   : dek02

==================================================================================
FITUR UNGGULAN
==================================================================================
1. Tracking Status Real-time
   Mahasiswa dapat memantau posisi surat secara langsung (Pending/Approved/Rejected)
   di setiap tahap birokrasi.

2. Dashboard Analisis Eksekutif
   Admin memiliki akses ke dashboard visual yang menyajikan:
   - Tren Surat Masuk (Line Chart)
   - Total per Jenis Surat (Bar Chart)
   - Rasio Approval (Pie Chart)
   - Rata-rata Durasi Layanan (Horizontal Bar)

3. Integrasi Data Warehouse (ETL)
   Sistem tidak membebani database operasional saat melakukan analisis berat, 
   karena menggunakan skema OLAP (Star Schema) yang diproses via Pentaho.

4. Notifikasi Urgency (Early Warning System)
   Sistem memberikan tanda visual (highlight merah/kuning) pada tabel admin 
   jika ada surat yang mandek/belum diproses lebih dari 3 atau 7 hari.