<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jobseeker_profiles', function (Blueprint $table) {
            $table->string('akun_media_sosial')->nullable()->after('status_disabilitas');
        });
    }

    public function down(): void
    {
        Schema::table('jobseeker_profiles', function (Blueprint $table) {
            $table->dropColumn('akun_media_sosial');
        });
    }
};

