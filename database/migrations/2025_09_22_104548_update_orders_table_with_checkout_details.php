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
        Schema::table('orders', function (Blueprint $table) {
            // Add the missing columns
            $table->text('shipping_address')->nullable()->after('status');
            $table->text('billing_address')->nullable()->after('shipping_address');
            $table->string('payment_method')->nullable()->after('billing_address');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('shipping_method')->nullable()->after('payment_status');
            $table->string('tracking_number')->nullable()->after('shipping_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_address',
                'billing_address',
                'payment_method',
                'payment_status',
                'shipping_method',
                'tracking_number'
            ]);
        });
    }
};
