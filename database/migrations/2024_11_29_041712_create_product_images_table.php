<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Create a new table without the 'images' column
        Schema::create('new_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            // Add other columns from the original 'products' table, except 'images'
        });

        // Step 2: Copy data from the old table to the new table
        DB::table('new_products')->insert(
            DB::table('products')->select('id', 'name', 'price', 'description', 'created_at', 'updated_at')->get()->toArray()
        );

        // Step 3: Drop the old 'products' table
        Schema::dropIfExists('products');

        // Step 4: Rename the new table to 'products'
        Schema::rename('new_products', 'products');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Create the old table structure again (including the 'images' column)
        Schema::create('old_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->string('images')->nullable(); // Reintroduce 'images' column
            $table->timestamps();
        });

        // Step 2: Copy data back from the new 'products' table to the old one
        DB::table('old_products')->insert(
            DB::table('products')->select('id', 'name', 'price', 'description', 'created_at', 'updated_at')->get()->toArray()
        );

        // Step 3: Drop the 'products' table
        Schema::dropIfExists('products');

        // Step 4: Rename the old table back to 'products'
        Schema::rename('old_products', 'products');
    }
};
