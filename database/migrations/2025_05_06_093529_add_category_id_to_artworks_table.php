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
        Schema::table('artworks', function (Blueprint $table) {
            Schema::table('artworks', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable();

                // Optional: Add a foreign key constraint
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            //
        });
    }
};
