<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_profile_id')->constrained()->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->string('jabatan');
            $table->year('tahun_mulai');
            $table->year('tahun_selesai')->nullable();
            $table->string('surat_pengalaman')->nullable(); // file upload (PDF/JPG)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};

