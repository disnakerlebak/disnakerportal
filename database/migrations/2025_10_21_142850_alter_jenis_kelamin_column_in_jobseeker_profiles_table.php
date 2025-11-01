<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobseeker_profiles', function (Blueprint $table) {
            $table->string('jenis_kelamin', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jobseeker_profiles', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->change();
        });
    }
};
