<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jobseeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Data Diri
            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama')->nullable();
            $table->enum('status_perkawinan', ['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'])->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('domisili_kecamatan')->nullable(); // isi 28 opsi nanti
            // Field terkunci (diset saat pertama kali, lalu tidak bisa diubah)
            $table->string('no_telepon')->nullable();
            $table->string('email_cache')->nullable(); // salinan email user saat pertama isi
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('jobseeker_profiles');
    }
};

