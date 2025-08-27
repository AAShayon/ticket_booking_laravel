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
        Schema::table('operator_requests', function (Blueprint $table) {
            $table->string('operator_name')->nullable()->change();
            $table->string('route_details')->nullable()->change();
            $table->string('fare_details')->nullable()->change();
            $table->decimal('admin_commission_percentage', 5, 2)->nullable()->change();
            $table->string('requested_role')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operator_requests', function (Blueprint $table) {
            $table->string('operator_name')->nullable(false)->change();
            $table->string('route_details')->nullable(false)->change();
            $table->string('fare_details')->nullable(false)->change();
            $table->decimal('admin_commission_percentage', 5, 2)->nullable(false)->change();
            $table->dropColumn('requested_role');
        });
    }
};
