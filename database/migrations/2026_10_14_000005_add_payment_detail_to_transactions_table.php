<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('paypal_account_name')->nullable()->after('email');
            $table->string('bank_name')->nullable()->after('payable_amount');
            $table->string('bank_account_name')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_account_name');
            $table->string('payout_type')->nullable()->after('bank_account_number');
            $table->string('payout_method')->nullable()->after('payout_type');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'paypal_account_name',
                'bank_name',
                'bank_account_name',
                'bank_account_number',
                'payout_type',
                'payout_method',
            ]);
        });
    }
};
