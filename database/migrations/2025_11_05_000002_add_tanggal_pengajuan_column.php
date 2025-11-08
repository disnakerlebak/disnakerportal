<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('card_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('card_applications', 'tanggal_pengajuan')) {
                $afterColumn = Schema::hasColumn('card_applications', 'type') ? 'type' : 'status';
                $table->timestamp('tanggal_pengajuan')->nullable()->after($afterColumn);
            }
        });
    }

    public function down(): void
    {
        Schema::table('card_applications', function (Blueprint $table) {
            if (Schema::hasColumn('card_applications', 'tanggal_pengajuan')) {
                $table->dropColumn('tanggal_pengajuan');
            }
        });
    }
};
