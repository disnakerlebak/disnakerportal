<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah opsi status "draft" agar lowongan bisa disimpan/preview sebelum tayang
        DB::statement("ALTER TABLE job_postings MODIFY status ENUM('draft','aktif','tutup') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Kembali ke enum awal (aktif/tutup) dengan default aktif
        DB::statement("ALTER TABLE job_postings MODIFY status ENUM('aktif','tutup') NOT NULL DEFAULT 'aktif'");
    }
};
