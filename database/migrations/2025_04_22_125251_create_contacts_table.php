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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('organisation_record')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('image')->nullable();
            $table->string('position')->nullable();
            $table->string('organisation')->nullable();
            $table->string('importance')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('addional_phone_number')->nullable();
            $table->string('website')->nullable();
            $table->string('addional_website')->nullable();
            $table->string('address')->nullable();
            $table->string('gender')->nullable();
            $table->string('spoken_languages')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->double('max_budget',8,4)->default(0);
            $table->string('consultant')->nullable();
            $table->string('secondary_consultant')->nullable();
            $table->string('contact_visibility')->nullable();
            $table->string('mailing_settings')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};