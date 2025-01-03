<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sender_id');
                $table->unsignedBigInteger('receiver_id');
                $table->text('content');
                $table->boolean('is_read')->default(false); // To track read status
                $table->string('media_path')->nullable();   // To store media file paths
                $table->timestamps();
        
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            });
    
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
