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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method')->after('status'); // e.g., 'cash', 'online'
            $table->string('payment_name')->nullable()->after('payment_method'); // e.g., 'Cash', 'Stripe', 'PayPal'
            $table->string('transaction_id')->nullable()->after('payment_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_name');
            $table->dropColumn('transaction_id');
        });
    }
};