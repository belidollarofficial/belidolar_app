<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['bank_name' => 'BCA', 'account_name' => 'Beli Dolar', 'account_number' => '1234567890', 'sort_order' => 1],
            ['bank_name' => 'Mandiri', 'account_name' => 'Beli Dolar', 'account_number' => '9876543210', 'sort_order' => 2],
            ['bank_name' => 'BNI', 'account_name' => 'Beli Dolar', 'account_number' => '5544332211', 'sort_order' => 3],
        ];

        foreach ($accounts as $account) {
            BankAccount::updateOrCreate(
                ['bank_name' => $account['bank_name'], 'account_number' => $account['account_number']],
                $account
            );
        }
    }
}
