<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE card_applications
            MODIFY status ENUM(
                'Menunggu Verifikasi',
                'Menunggu Revisi Verifikasi',
                'Revisi Diminta',
                'Disetujui',
                'Ditolak'
            ) DEFAULT 'Menunggu Verifikasi'
        ");

        DB::table('card_applications')
            ->where('status', 'Menunggu')
            ->update(['status' => 'Menunggu Verifikasi']);
    }

    public function down(): void
    {
        DB::table('card_applications')
            ->whereIn('status', ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi'])
            ->update(['status' => 'Menunggu']);

        DB::statement("
            ALTER TABLE card_applications
            MODIFY status ENUM('Menunggu', 'Disetujui', 'Ditolak') DEFAULT 'Menunggu'
        ");
    }
};

