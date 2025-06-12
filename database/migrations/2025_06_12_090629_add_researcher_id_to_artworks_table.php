<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('artworks', function (Blueprint $table) {
            // Add nullable researcher_id column after author_id
            $table->unsignedBigInteger('researcher_id')
                  ->nullable()
                  ->after('author_id');
                  
            // Add foreign key constraint to users table
            $table->foreign('researcher_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // or 'cascade' based on your needs
                  
            // Add researcher_name column for manual entries
            $table->string('researcher_name')
                  ->nullable()
                  ->after('researcher_id');
        });
    }

    public function down()
    {
        Schema::table('artworks', function (Blueprint $table) {
            // Drop foreign key first to avoid errors
            $table->dropForeign(['researcher_id']);
            
            // Remove the columns
            $table->dropColumn(['researcher_id', 'researcher_name']);
        });
    }
};