<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('tipe_pekerjaan')->nullable()->after('lokasi_kerja');
            $table->string('model_kerja')->nullable()->after('tipe_pekerjaan');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['tipe_pekerjaan', 'model_kerja']);
        });
    }
};
