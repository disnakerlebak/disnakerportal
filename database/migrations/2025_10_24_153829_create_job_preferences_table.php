<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('minat_lokasi')->nullable();   // ["Kabupaten Lebak", "Luar Negeri", ...]
            $table->json('minat_bidang')->nullable();   // ["IT", "Jasa", "Pertambangan", ...]
            $table->string('gaji_harapan')->nullable(); // "3-5 juta", ">10 juta", dll
            $table->text('deskripsi_diri')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_preferences');
    }
};
