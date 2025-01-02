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
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary(); // Session ID
                $table->foreignId('user_id')->nullable()->index(); // For user sessions
                $table->string('ip_address', 45)->nullable(); // IP Address
                $table->text('user_agent')->nullable(); // User agent string
                $table->text('payload'); // Session data
                $table->integer('last_activity')->index(); // Last activity timestamp
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
