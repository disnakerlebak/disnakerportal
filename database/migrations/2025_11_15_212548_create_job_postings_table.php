<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');

            $table->string('judul');
            $table->string('posisi')->nullable();

            $table->text('deskripsi')->nullable();
            $table->text('kualifikasi')->nullable();

            $table->string('pendidikan_minimal')->nullable();
            $table->string('jenis_kelamin')->nullable(); // L/P/Keduanya
            $table->integer('usia_min')->nullable();
            $table->integer('usia_max')->nullable();

            $table->integer('gaji_min')->nullable();
            $table->integer('gaji_max')->nullable();

            $table->string('lokasi_kerja');

            $table->boolean('menerima_disabilitas')->default(true);

            $table->date('tanggal_posting')->nullable();
            $table->date('tanggal_expired')->nullable();

            $table->enum('status', ['aktif', 'tutup'])->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
