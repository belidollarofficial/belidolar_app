<?php

namespace Database\Seeders;

use App\Models\PayoutMethod;
use Illuminate\Database\Seeder;

class PayoutMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['type' => 'bank', 'name' => 'BCA', 'sort_order' => 1],
            ['type' => 'bank', 'name' => 'Mandiri', 'sort_order' => 2],
            ['type' => 'bank', 'name' => 'BNI', 'sort_order' => 3],
            ['type' => 'ewallet', 'name' => 'DANA', 'sort_order' => 1],
            ['type' => 'ewallet', 'name' => 'OVO', 'sort_order' => 2],
            ['type' => 'ewallet', 'name' => 'GoPay', 'sort_order' => 3],
            ['type' => 'ewallet', 'name' => 'ShopeePay', 'sort_order' => 4],
        ];

        foreach ($methods as $method) {
            PayoutMethod::updateOrCreate(
                ['type' => $method['type'], 'name' => $method['name']],
                $method
            );
        }
    }
}
