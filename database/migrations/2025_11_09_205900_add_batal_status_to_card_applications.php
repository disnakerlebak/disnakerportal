<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah nilai 'Batal' ke ENUM status
        DB::statement("
            ALTER TABLE card_applications
            MODIFY status ENUM(
                'Menunggu Verifikasi',
                'Menunggu Revisi Verifikasi',
                'Revisi Diminta',
                'Batal',
                'Disetujui',
                'Ditolak',
                'Dicetak',
                'Diambil'
            ) DEFAULT 'Menunggu Verifikasi'
        ");
    }

    public function down(): void
    {
        // Kembalikan tanpa 'Batal' (tetap sisakan nilai lain)
        DB::statement("
            ALTER TABLE card_applications
            MODIFY status ENUM(
                'Menunggu Verifikasi',
                'Menunggu Revisi Verifikasi',
                'Revisi Diminta',
                'Disetujui',
                'Ditolak',
                'Dicetak',
                'Diambil'
            ) DEFAULT 'Menunggu Verifikasi'
        ");
    }
};

