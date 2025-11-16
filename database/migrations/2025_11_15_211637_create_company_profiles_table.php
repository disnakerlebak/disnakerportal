<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('nama_perusahaan');
            $table->string('logo')->nullable();
            $table->string('jenis_usaha')->nullable();

            $table->string('alamat_lengkap');
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();

            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            $table->text('deskripsi')->nullable();
            $table->integer('jumlah_karyawan')->nullable();

            $table->string('nib')->nullable();
            $table->string('npwp')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
