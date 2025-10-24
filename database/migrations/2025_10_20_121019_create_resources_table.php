<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            // *** START FIX: Change from $table->id() to $table->uuid() ***
            $table->uuid('id')->primary();
            // *** END FIX ***

            // Add the other columns from your model
            $table->foreignId('course_id')->index();
            $table->string('title');
            $table->text('content');
            $table->json('embedding')->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};