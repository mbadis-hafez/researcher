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
        Schema::create('art_work_collection', function (Blueprint $table) {
         $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->unsignedBigInteger('artwork_id');
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->foreign('artwork_id')->references('id')->on('artworks')->onDelete('cascade');

            $table->unique(['collection_id', 'artwork_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artwork_collection');
    }
};
