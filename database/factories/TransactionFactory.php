<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'transaction_code' => 'ZP-'.now()->format('ymd').'-'.strtoupper($this->faker->lexify('?????')),
            'customer_name' => $this->faker->name(),
            'customer_phone' => '628'.$this->faker->numerify('##########'),
            'email' => $this->faker->safeEmail(),
            'service' => $this->faker->randomElement(array_keys(Transaction::SERVICES)),
            'payment_type' => null,
            'amount' => $this->faker->randomFloat(2, 5, 500),
            'notes' => null,
            'status' => 'pending',
            'source' => 'website',
        ];
    }

    public function status(string $status): static
    {
        return $this->state(fn () => ['status' => $status]);
    }
}
