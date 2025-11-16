<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_posting_id')->constrained('job_postings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');

            $table->date('tanggal_lamaran')->nullable();

            $table->enum('status', [
                'dikirim',
                'dibuka',
                'diproses',
                'wawancara',
                'diterima',
                'ditolak'
            ])->default('dikirim');

            $table->text('catatan_perusahaan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
