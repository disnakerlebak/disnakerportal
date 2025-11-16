<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('social_facebook')->nullable()->after('website');
            $table->string('social_instagram')->nullable()->after('social_facebook');
            $table->string('social_linkedin')->nullable()->after('social_instagram');
            $table->string('social_twitter')->nullable()->after('social_linkedin');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'social_facebook',
                'social_instagram',
                'social_linkedin',
                'social_twitter',
            ]);
        });
    }
};

