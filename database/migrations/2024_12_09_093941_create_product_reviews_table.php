<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_reviews')) {
            Schema::create('product_reviews', function (Blueprint $table) {
                $table->id();
                // Explicitly define foreign key for `product_id` referencing `products.id`
                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

                // Explicitly define foreign key for `user_id` referencing `users.id`
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                $table->tinyInteger('rating')->unsigned();
                $table->text('review');
                $table->timestamps();
            });
    
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('product_reviews');
    }
}
