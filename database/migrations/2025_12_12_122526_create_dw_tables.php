<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // --- 1. TABEL DIMENSI ---

        // Dimensi Waktu
        Schema::create('dim_waktu', function (Blueprint $table) {
            $table->unsignedBigInteger('id_waktu')->primary(); // Format: YYYYMMDD (misal 20251022) - Diisi Pentaho
            $table->date('tanggal');
            $table->integer('tahun');
            $table->string('kuartal', 2); 
            $table->string('bulan', 20);
            $table->string('hari', 20);
            $table->integer('minggu_ke');
        });

        // Dimensi Mahasiswa
        Schema::create('dim_mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa'); // Surrogate Key (Auto Increment)
            $table->string('nim', 20)->index(); 
            $table->string('nama_mahasiswa');
            $table->string('prodi');
            $table->integer('angkatan');
            $table->string('fakultas');
        });

        // Dimensi Jenis Surat
        Schema::create('dim_jenis_surat', function (Blueprint $table) {
            $table->id('id_jenis_surat');
            $table->string('jenis_surat'); 
            $table->string('tipe_surat');  
        });

        // Dimensi Organisasi
        Schema::create('dim_organisasi', function (Blueprint $table) {
            $table->id('id_organisasi');
            $table->string('nama_organisasi');
            $table->string('tipe_organisasi'); 
        });

        // Dimensi Admin
        Schema::create('dim_admin', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('nama_admin');
            $table->string('role');
            $table->string('jabatan')->nullable();
        });

        // --- 2. TABEL FAKTA ---

        // Fact Pengajuan
        Schema::create('fact_pengajuan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_waktu');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_jenis_surat');
            $table->unsignedBigInteger('id_organisasi');
            $table->integer('jumlah_pengajuan')->default(1);
            
            // Indexing untuk performa query dashboard
            $table->index(['id_waktu', 'id_jenis_surat']); 
        });

        // Fact Durasi Layanan
        Schema::create('fact_durasi_layanan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_waktu');
            $table->unsignedBigInteger('id_jenis_surat');
            $table->unsignedBigInteger('id_organisasi');
            $table->integer('durasi_jam');
            $table->integer('durasi_hari');
        });

        // Fact Status Approval
        Schema::create('fact_status_approval', function (Blueprint $table) {
            $table->unsignedBigInteger('id_waktu');
            $table->unsignedBigInteger('id_admin');
            $table->unsignedBigInteger('id_jenis_surat');
            $table->integer('total_setuju')->default(0);
            $table->integer('total_tolak')->default(0);
        });
    }

    public function down()
    {
        // Hapus tabel urut dari Fact dulu baru Dimensi (menghindari error FK jika ada)
        Schema::dropIfExists('fact_status_approval');
        Schema::dropIfExists('fact_durasi_layanan');
        Schema::dropIfExists('fact_pengajuan');
        Schema::dropIfExists('dim_admin');
        Schema::dropIfExists('dim_organisasi');
        Schema::dropIfExists('dim_jenis_surat');
        Schema::dropIfExists('dim_mahasiswa');
        Schema::dropIfExists('dim_waktu');
    }
};