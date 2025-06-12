<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('artworks', function (Blueprint $table) {
            // Add nullable author_id (if some artworks may not have an author)
            $table->unsignedBigInteger('author_id')->nullable();
            
            // Foreign key constraint (assuming 'authors' table exists)
            $table->foreign('author_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // or 'cascade' if needed
        });
    }

    public function down()
    {
        Schema::table('artworks', function (Blueprint $table) {
            // Drop foreign key first to avoid errors
            $table->dropForeign(['author_id']);
            
            // Remove the column
            $table->dropColumn('author_id');
        });
    }
};