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
        Schema::create('artist_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id')->nullable();
            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_images');
    }
};