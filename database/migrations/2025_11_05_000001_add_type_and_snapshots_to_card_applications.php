<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $afterColumn = Schema::hasColumn('card_applications', 'picked_up_at')
            ? 'picked_up_at'
            : 'updated_at';

        Schema::table('card_applications', function (Blueprint $table) use ($afterColumn) {
            if (!Schema::hasColumn('card_applications', 'type')) {
                $table->enum('type', ['baru', 'perbaikan', 'perpanjangan'])->default('baru')->after('status');
            }
            if (!Schema::hasColumn('card_applications', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('type')->constrained('card_applications')->nullOnDelete();
            }
            if (!Schema::hasColumn('card_applications', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('parent_id');
            }
            if (!Schema::hasColumn('card_applications', 'snapshot_before')) {
                $table->json('snapshot_before')->nullable()->after($afterColumn);
            }
            if (!Schema::hasColumn('card_applications', 'snapshot_after')) {
                $table->json('snapshot_after')->nullable()->after('snapshot_before');
            }
        });
    }

    public function down(): void
    {
        Schema::table('card_applications', function (Blueprint $table) {
            if (Schema::hasColumn('card_applications', 'snapshot_after')) {
                $table->dropColumn('snapshot_after');
            }
            if (Schema::hasColumn('card_applications', 'snapshot_before')) {
                $table->dropColumn('snapshot_before');
            }
            if (Schema::hasColumn('card_applications', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('card_applications', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            if (Schema::hasColumn('card_applications', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
