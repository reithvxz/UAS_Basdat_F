# 📨 SIMAS-FTMM: Integrated Mail Management & Data Warehouse

[![Laravel](https://img.shields.io/badge/Framework-Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![Pentaho](https://img.shields.io/badge/ETL-Pentaho-0E599F?style=flat-square&logo=hitachi&logoColor=white)](https://www.hitachi-solutions.com/)
[![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)

**SIMAS-FTMM** (Sistem Manajemen Persuratan FTMM) adalah proyek integrasi sistem operasional dan analitik yang dirancang untuk mendigitalkan birokrasi pengajuan surat kemahasiswaan di lingkungan FTMM Universitas Airlangga. Proyek ini merupakan tugas akhir kelompok untuk mata kuliah **Basis Data**.

## 👥 Kelompok F - Kontributor
Proyek ini dikembangkan secara kolaboratif oleh:
- **Okan Athallah Maredith** (164231088)
- **Ratu Aphrodite Cinta Aurora** (164231009)
- **Ario Rizky Muhammad** (164231080)
- **Bunga Amanda Aurora** (164231098)
- **Athalia Andria Loly Aruan** (164231110)

## 🏗️ Arsitektur Sistem
Sistem ini menggunakan pendekatan hibrida antara pengolahan transaksi (OLTP) dan pengolahan analitik (OLAP):
1. **Modul Operasional (Laravel):** Menangani workflow persuratan secara real-time mulai dari pengajuan mahasiswa hingga approval Dekanat.
2. **Modul Analitik (Pentaho PDI):** Menjalankan proses ETL (Extract, Transform, Load) untuk memindahkan data operasional ke dalam Data Warehouse dengan Star Schema.
3. **Executive Dashboard:** Visualisasi tren data, performa layanan, dan durasi approval menggunakan Chart.js.

## 🛠️ Spesifikasi Teknologi
- **Backend:** PHP 8.x (Laravel Framework)
- **Database:** MySQL (OLTP & OLAP Schema)
- **ETL Tool:** Pentaho Data Integration (Spoon)
- **Frontend:** Blade Templating, Bootstrap, Custom CSS
- **Analytics:** Chart.js (Real-time tracking & Reporting)

## 📂 Struktur Repositori
- **Laporan/**: Dokumentasi teknis dan laporan akhir proyek (PDF).
- **Codingan_Laravel/**: Source code aplikasi web operasional.
- **Data_Pentaho/**: File transformasi ETL (.ktr) dan database dump (.sql).

## ⚙️ Panduan Instalasi (How to Run)

### 1. Database Setup
- Buat database baru bernama: **persuratan**
- Import file **persuratan.sql** yang berada di folder Data_Pentaho.

### 2. Laravel Configuration
Jalankan perintah berikut di terminal folder Codingan_Laravel:
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan serve
(Pastikan konfigurasi .env untuk database sudah sesuai dengan MySQL lokal Anda).

### 3. ETL Process (Pentaho)
Buka Pentaho Spoon, lalu jalankan file .ktr di folder Data_Pentaho dengan urutan:
1. **Dimensi:** 01_dim_mahasiswa, 02_dim_waktu, 03_dims_lain.
2. **Fakta:** 04_fact_pengajuan, 05_fact_durasi, 06_fact_approval.

## 🔑 Akses Uji Coba
- **Mahasiswa:** User: 164231088 | Pass: okan
- **Admin/Approver:** User: bem01 / akd01 / dek01 | Pass: 12345

---
Kelompok F Basis Data Teknologi Sains Data FTMM UNAIR.
Proyek ini mensimulasikan alur birokrasi nyata dengan fitur Early Warning System untuk efisiensi layanan publik.
