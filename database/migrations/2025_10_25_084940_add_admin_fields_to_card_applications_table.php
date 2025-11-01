<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('card_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('card_applications', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('card_applications', 'revision_notes')) {
                $table->text('revision_notes')->nullable()->after('alasan_penolakan');
            }
            if (!Schema::hasColumn('card_applications', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('tanggal_verifikasi');
            }
            if (!Schema::hasColumn('card_applications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('card_applications', 'revision_requested_at')) {
                $table->timestamp('revision_requested_at')->nullable()->after('rejected_at');
            }
            if (!Schema::hasColumn('card_applications', 'printed_at')) {
                $table->timestamp('printed_at')->nullable()->after('revision_requested_at');
            }
            if (!Schema::hasColumn('card_applications', 'picked_up_at')) {
                $table->timestamp('picked_up_at')->nullable()->after('printed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('card_applications', function (Blueprint $table) {
            // turunkan hanya jika ada
            foreach ([
                'assigned_to','revision_notes','approved_at','rejected_at',
                'revision_requested_at','printed_at','picked_up_at'
            ] as $col) {
                if (Schema::hasColumn('card_applications', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
