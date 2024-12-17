<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add or modify columns as needed
            if (!Schema::hasColumn('products', 'new_column')) {
                $table->string('new_column')->nullable(); // Example column
            }

            // Additional modifications
            // $table->string('another_column')->default('default_value');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Rollback the added/modified columns
            $table->dropColumn('new_column');
            // $table->dropColumn('another_column');
        });
    }
}
