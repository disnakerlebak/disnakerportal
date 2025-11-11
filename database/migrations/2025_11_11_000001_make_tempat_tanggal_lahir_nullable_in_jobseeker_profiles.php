<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Gunakan SQL mentah agar tidak perlu doctrine/dbal
        DB::statement('ALTER TABLE jobseeker_profiles MODIFY COLUMN tempat_lahir VARCHAR(255) NULL');
        DB::statement('ALTER TABLE jobseeker_profiles MODIFY COLUMN tanggal_lahir DATE NULL');
    }

    public function down(): void
    {
        // Kembalikan ke NOT NULL (akan gagal jika ada data NULL)
        DB::statement("ALTER TABLE jobseeker_profiles MODIFY COLUMN tempat_lahir VARCHAR(255) NOT NULL");
        DB::statement('ALTER TABLE jobseeker_profiles MODIFY COLUMN tanggal_lahir DATE NOT NULL');
    }
};

