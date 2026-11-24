<?php

namespace Tests\Feature;

use App\Livewire\LandingPage\Components\OrderForm;
use App\Models\BankAccount;
use App\Models\PayoutMethod;
use App\Models\User;
use App\Support\SiteContent;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentDataTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteContent::flush();
    }

    private function admin(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_admin_can_manage_bank_accounts(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.bank-accounts.store'), [
            'bank_name' => 'BCA',
            'account_name' => 'Beli Dolar',
            'account_number' => '1234567890',
            'sort_order' => 1,
        ])->assertRedirect();

        $this->assertDatabaseHas('bank_accounts', [
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
        ]);

        $account = BankAccount::first();

        $this->put(route('admin.bank-accounts.update', $account), [
            'bank_name' => 'BCA',
            'account_name' => 'Beli Dolar Baru',
            'account_number' => '9999999999',
            'sort_order' => 2,
        ])->assertRedirect(route('admin.bank-accounts.index'));

        $this->assertDatabaseHas('bank_accounts', [
            'id' => $account->id,
            'account_name' => 'Beli Dolar Baru',
            'account_number' => '9999999999',
        ]);

        $this->delete(route('admin.bank-accounts.destroy', $account))->assertRedirect();
        $this->assertDatabaseMissing('bank_accounts', ['id' => $account->id]);
    }

    public function test_admin_can_manage_payout_methods(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.payout-methods.store'), [
            'type' => 'ewallet',
            'name' => 'DANA',
            'sort_order' => 1,
        ])->assertRedirect();

        $this->assertDatabaseHas('payout_methods', ['type' => 'ewallet', 'name' => 'DANA']);

        $method = PayoutMethod::first();

        $this->put(route('admin.payout-methods.update', $method), [
            'type' => 'bank',
            'name' => 'BCA',
            'sort_order' => 3,
        ])->assertRedirect(route('admin.payout-methods.index'));

        $this->assertDatabaseHas('payout_methods', [
            'id' => $method->id,
            'type' => 'bank',
            'name' => 'BCA',
        ]);

        $this->delete(route('admin.payout-methods.destroy', $method))->assertRedirect();
        $this->assertDatabaseMissing('payout_methods', ['id' => $method->id]);
    }

    public function test_payout_method_type_must_be_valid(): void
    {
        $this->actingAs($this->admin());

        $this->from(route('admin.payout-methods.index'))
            ->post(route('admin.payout-methods.store'), [
                'type' => 'crypto',
                'name' => 'Bitcoin',
            ])
            ->assertSessionHasErrors('type');

        $this->assertDatabaseCount('payout_methods', 0);
    }

    public function test_order_form_shows_operational_hours_and_processing_time(): void
    {
        $this->seed(ContentSeeder::class);
        SiteContent::flush();

        Livewire::test(OrderForm::class)
            ->assertSee('Jam Operasional')
            ->assertSee('Senin - Minggu (08:00 - 24:00)')
            ->assertSee('Hari besar, tanggal merah tetap buka')
            ->assertSee('Rata - rata pesanan di proses')
            ->assertSee('5-15 menit')
            ->assertSee('Catatan: Jika transaksi melewati jam operasional')
            ->assertDontSee('Lama pesanan diproses');
    }

    public function test_order_form_redirects_to_whatsapp_without_payment_page(): void
    {
        $this->seed(ContentSeeder::class);
        SiteContent::flush();

        $bank = BankAccount::create([
            'bank_name' => 'BCA',
            'account_name' => 'Beli Dolar',
            'account_number' => '1234567890',
            'sort_order' => 1,
        ]);

        Livewire::test(OrderForm::class)
            ->set([
                'name' => 'Budi',
                'phone' => '081290859343',
                'paypalAccountName' => 'Budi',
                'service' => 'topup',
                'amount' => 50,
                'bankAccountId' => $bank->id,
            ])
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSee('berhasil dibuat')
            ->assertDontSee('Selesaikan Pembayaran');
    }
}
