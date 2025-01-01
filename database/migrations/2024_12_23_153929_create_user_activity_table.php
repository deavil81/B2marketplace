<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('user_activities')) {
            Schema::create('user_activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('activity_type');
                $table->text('activity_data')->nullable(); // You can store JSON data about the activity if needed
                $table->timestamps();
                
                // Adding foreign key constraint
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                // Adding index for better performance on user_id
                $table->index('user_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('user_activities');
    }
}
