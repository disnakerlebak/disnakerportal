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
                'Ditolak',
                'Dicetak',
                'Diambil'
            ) DEFAULT 'Menunggu Verifikasi'
        ");
    }

    public function down(): void
    {
        DB::table('card_applications')
            ->whereIn('status', ['Dicetak', 'Diambil'])
            ->update(['status' => 'Disetujui']);

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
    }
};

