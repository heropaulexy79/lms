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
        Schema::table('courses', function (Blueprint $table) {
            // This boolean will mark a course as a "Template"
            $table->boolean('is_template')->default(false)->after('is_published');
            
            // We also need to make organisation_id nullable, 
            // so template courses don't belong to anyone.
            $table->foreignId('organisation_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('is_template');
            $table->foreignId('organisation_id')->nullable(false)->change();
        });
    }
};
