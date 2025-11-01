<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('card_applications', function (Blueprint $table) {
            $table->string('ktp_file')->nullable()->after('foto_closeup');
            $table->string('ijazah_file')->nullable()->after('ktp_file');
            $table->timestamp('tanggal_pengajuan')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('card_applications', function (Blueprint $table) {
            $table->dropColumn(['foto_closeup', 'ktp_file', 'ijazah_file', 'status', 'tanggal_pengajuan']);
        });
    }
};
