<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->enum('verification_status', ['pending', 'approved'])
                ->default('pending')
                ->after('npwp');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'verified_at']);
        });
    }
};

