<?php

namespace Tests\Feature;

use App\Livewire\LandingPage\Components\TransactionTracking;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TransactionTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_track_transaction_with_code_and_phone(): void
    {
        $transaction = Transaction::factory()->create([
            'transaction_code' => 'ZP-260609-AB12C',
            'customer_phone' => '6281290859343',
            'status' => 'diproses',
        ]);

        Livewire::test(TransactionTracking::class)
            ->set('code', 'zp-260609-ab12c')
            ->set('phone', '081290859343')
            ->call('search')
            ->assertHasNoErrors()
            ->assertSet('transaction.id', $transaction->id);
    }

    public function test_tracking_fails_with_wrong_phone_number(): void
    {
        Transaction::factory()->create([
            'transaction_code' => 'ZP-260609-AB12D',
            'customer_phone' => '6281290859343',
        ]);

        Livewire::test(TransactionTracking::class)
            ->set('code', 'ZP-260609-AB12D')
            ->set('phone', '081200000000')
            ->call('search')
            ->assertHasErrors('code')
            ->assertSet('transaction', null);
    }

    public function test_landing_page_shows_tracking_section(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Lacak Transaksi');
    }
}
