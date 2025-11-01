<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_application_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('card_application_id')->constrained()->cascadeOnDelete();
            $t->foreignId('actor_id')->constrained('users'); // admin yang bertindak
            $t->string('action', 50);                        // approve/reject/revision/printed/picked_up
            $t->string('from_status', 30)->nullable();
            $t->string('to_status', 30)->nullable();
            $t->text('notes')->nullable();                   // alasan/tambahan
            $t->string('ip', 45)->nullable();
            $t->string('user_agent')->nullable();
            $t->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
