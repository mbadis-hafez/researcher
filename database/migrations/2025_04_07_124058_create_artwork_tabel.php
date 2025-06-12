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
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description')->nullable();
            $table->string('stock_number')->nullable();
            $table->string('year')->nullable();
            $table->text('medium')->nullable();
            $table->text('dimensions')->nullable();
            $table->text('signed_dated')->nullable();
            $table->string('importance')->nullable();
            $table->string('artwork_type')->nullable();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete()->nullable();
            $table->text('image_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artwork_tabel');
    }
};
