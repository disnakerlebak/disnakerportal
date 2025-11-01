<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_application_id')->constrained('card_applications')->onDelete('cascade');
            $table->string('type', 50); // contoh: 'foto_closeup', 'ktp', 'ijazah'
            $table->string('file_path', 255); // path ke file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_application_documents');
    }
};
