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
            $table->string('inventory_number')->after('stock_number')->nullable();
            $table->string('condition_report')->after('dimensions')->nullable();
            $table->string('provenance')->after('condition_report')->nullable();
            $table->string('status')->after('provenance')->nullable();
            $table->decimal('price', 12, 2)->after('status')->nullable();
            $table->string('contract_type')->after('price')->nullable();
            $table->string('contract_duration')->after('contract_type')->nullable();
            $table->date('contract_start_date')->after('contract_duration')->nullable();
            $table->date('contract_end_date')->after('contract_start_date')->nullable();
            $table->string('contract_validity')->after('contract_end_date')->nullable();
            $table->decimal('gallery_commission', 5, 2)->after('contract_validity')->nullable();
            $table->decimal('artist_share', 5, 2)->after('gallery_commission')->nullable();
            $table->string('edition')->after('artist_share')->nullable();
            $table->string('nationality')->after('artist_id')->nullable();
            $table->string('current_location')->after(column: 'nationality')->nullable();
            $table->string('signature')->after(column: 'current_location')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('art_works', function (Blueprint $table) {
            $table->dropColumn([
                'inventory_number',
                'condition_report',
                'provenance',
                'status',
                'price',
                'contract_type',
                'contract_duration',
                'contract_start_date',
                'contract_end_date',
                'contract_validity',
                'gallery_commission',
                'artist_share',
                'edition',
                'nationality',
                'current_location'
            ]);
            
            // Revert images change if needed
            $table->text('image_path')->nullable()->change();
        });
    }
};
