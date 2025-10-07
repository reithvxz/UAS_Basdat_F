<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('ormawas', function (Blueprint $table) {
            $table->id('ormawa_id');
            $table->string('nama_ormawa');
            $table->enum('tipe', ['HIMA', 'BSO', 'BEM']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('ormawas');
    }
};