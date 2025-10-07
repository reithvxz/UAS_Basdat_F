<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats', 'surat_id')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('file_path');
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lampirans');
    }
};