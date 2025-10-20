<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Use a NAMED class that matches the filename
class RemoveTrxfFromSubscriptionsTable extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {

            // 1. Drop the index first
            // The name comes from your error message.
            $table->dropUnique('billing_histories_transaction_ref_unique');

            // 2. Now you can safely drop the column
            $table->dropColumn('transaction_ref');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Re-add the column and index in reverse order
            $table->string('transaction_ref')->nullable();
            $table->unique('transaction_ref', 'billing_histories_transaction_ref_unique');
        });
    }
};