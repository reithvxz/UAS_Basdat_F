<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats', 'surat_id')->onDelete('cascade');
            $table->string('role');
            $table->string('status'); // Approved, Rejected
            $table->text('catatan')->nullable();
            $table->timestamp('approved_at')->useCurrent();
        });
    }
    public function down(): void {
        Schema::dropIfExists('approvals');
    }
};