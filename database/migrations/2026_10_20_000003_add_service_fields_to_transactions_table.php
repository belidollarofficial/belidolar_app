<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('account_type')->nullable();
            $table->string('payout_account_number')->nullable();
            $table->string('merchant_name')->nullable();
            $table->string('merchant_url', 500)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('fee_estimate')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'account_type',
                'payout_account_number',
                'merchant_name',
                'merchant_url',
                'payment_method',
                'fee_estimate',
            ]);
        });
    }
};
