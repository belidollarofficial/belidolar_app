<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use App\Support\SiteContent;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContentSeeder::class);
        SiteContent::flush();
    }

    private function admin(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('secret123'),
        ]);
    }

    private function transaction(array $overrides = []): Transaction
    {
        return Transaction::factory()->create($overrides);
    }

    public function test_admin_routes_require_authentication(): void
    {
        $this->get('/admin/transactions')->assertRedirect('/login');
        $this->get('/admin/transactions/1')->assertRedirect('/login');
    }

    public function test_admin_can_list_transactions(): void
    {
        $this->transaction(['customer_name' => 'Dewi Lestari', 'transaction_code' => 'ZP-2608-ABC12']);

        $this->actingAs($this->admin())->get('/admin/transactions')
            ->assertStatus(200)
            ->assertSee('Dewi Lestari')
            ->assertSee('ZP-2608-ABC12');
    }

    public function test_admin_can_filter_transactions_by_status(): void
    {
        $this->transaction(['transaction_code' => 'ZP-2608-AAAAA', 'status' => 'selesai']);
        $this->transaction(['transaction_code' => 'ZP-2608-BBBBB', 'status' => 'pending']);

        $this->actingAs($this->admin())->get('/admin/transactions?status=selesai')
            ->assertStatus(200)
            ->assertSee('ZP-2608-AAAAA')
            ->assertDontSee('ZP-2608-BBBBB');
    }

    public function test_admin_can_view_transaction_detail(): void
    {
        $transaction = $this->transaction(['customer_name' => 'Rina', 'service' => 'topup', 'amount' => 75, 'status' => 'pending']);

        $this->actingAs($this->admin())->get("/admin/transactions/{$transaction->id}")
            ->assertStatus(200)
            ->assertSee('Rina')
            ->assertSee($transaction->transaction_code);
    }

    public function test_admin_can_update_status_and_upload_proof(): void
    {
        Storage::fake('public');
        $transaction = $this->transaction(['status' => 'pending']);

        $response = $this->actingAs($this->admin())->put("/admin/transactions/{$transaction->id}", [
            'status' => 'selesai',
            'admin_note' => 'Pembayaran terkonfirmasi. Saldo sudah masuk.',
            'proof' => UploadedFile::fake()->createWithContent(
                'bukti.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='),
            ),
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'selesai',
            'admin_note' => 'Pembayaran terkonfirmasi. Saldo sudah masuk.',
        ]);

        $fresh = $transaction->fresh();
        $this->assertNotNull($fresh->proof_path);
        $this->assertStringStartsWith('proofs/', $fresh->proof_path);
        Storage::disk('public')->assertExists($fresh->proof_path);
    }

    public function test_proof_upload_is_limited_to_two_megabytes(): void
    {
        Storage::fake('public');
        $transaction = $this->transaction();

        // PNG valid 1x1 dengan tambahan data hingga melewati batas 2 MB.
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

        $this->actingAs($this->admin())->put("/admin/transactions/{$transaction->id}", [
            'status' => 'selesai',
            'proof' => UploadedFile::fake()->createWithContent(
                'bukti-besar.png',
                $png.str_repeat("\0", 3 * 1024 * 1024),
            ),
        ])->assertSessionHasErrors('proof');

        $this->assertNull($transaction->fresh()->proof_path);
    }

    public function test_admin_cannot_set_invalid_status(): void
    {
        $transaction = $this->transaction();

        $this->actingAs($this->admin())->put("/admin/transactions/{$transaction->id}", [
            'status' => 'luncuran',
        ])->assertSessionHasErrors('status');

        $this->assertSame('pending', $transaction->fresh()->status);
    }

    public function test_admin_notify_marks_notification_and_returns_wa_link(): void
    {
        $transaction = $this->transaction(['customer_name' => 'Budi', 'customer_phone' => '081290859343', 'status' => 'selesai']);

        $response = $this->actingAs($this->admin())->postJson("/admin/transactions/{$transaction->id}/notify")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('link', fn (string $link) => str_starts_with($link, 'https://wa.me/6281290859343?text='));

        $this->assertNotNull($transaction->fresh()->notified_at);

        $message = rawurldecode(explode('?text=', $response->json('link'), 2)[1] ?? '');
        $this->assertStringContainsString('selesai', strtolower($message));
        $this->assertStringContainsString($transaction->transaction_code, $message);
        $this->assertStringContainsString('Budi', $message);
    }

    public function test_admin_can_delete_transaction(): void
    {
        $transaction = $this->transaction();

        $this->actingAs($this->admin())->delete("/admin/transactions/{$transaction->id}")
            ->assertRedirect(route('admin.transactions.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('transactions', ['id' => $transaction->id]);
    }

    public function test_create_form_requires_authentication(): void
    {
        $this->get('/admin/transactions/create')->assertRedirect('/login');
    }

    public function test_admin_can_open_create_form(): void
    {
        $this->actingAs($this->admin())->get('/admin/transactions/create')
            ->assertStatus(200)
            ->assertSee('Tambah Transaksi Manual')
            ->assertSee('Top Up Saldo PayPal');
    }

    public function test_admin_can_store_manual_transaction(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/transactions', [
            'customer_name' => 'Siti Manual',
            'customer_phone' => '081234567890',
            'email' => 'siti@example.com',
            'service' => 'topup',
            'amount' => 150000,
            'payable_amount' => 2745000,
            'status' => 'selesai',
            'notes' => 'Transfer via BCA',
        ]);

        $transaction = Transaction::firstWhere('customer_name', 'Siti Manual');
        $this->assertNotNull($transaction);

        $response->assertRedirect(route('admin.transactions.show', $transaction))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'source' => 'manual',
            'status' => 'selesai',
            'service' => 'topup',
            'email' => 'siti@example.com',
            'customer_phone' => '6281234567890',
            'payable_amount' => 2745000,
        ]);

        $this->assertTrue($transaction->isManual());
        $this->assertSame('Input Manual', $transaction->source_label);
        $this->assertSame('Rp 2.745.000', $transaction->payable_label);
        $this->assertStringStartsWith('ZP-', $transaction->transaction_code);
    }

    public function test_manual_transaction_requires_valid_data(): void
    {
        $this->actingAs($this->admin())->post('/admin/transactions', [
            'customer_name' => '',
            'customer_phone' => '',
            'service' => 'tidak-ada',
            'amount' => 0,
            'status' => 'status-ngawur',
        ])->assertSessionHasErrors(['customer_name', 'customer_phone', 'service', 'amount', 'status']);

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_admin_can_store_manual_transaction_with_proof(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post('/admin/transactions', [
            'customer_name' => 'Andi Bukti',
            'customer_phone' => '081200000001',
            'service' => 'invoice',
            'amount' => 250000,
            'status' => 'selesai',
            'proof' => UploadedFile::fake()->createWithContent(
                'bukti.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='),
            ),
        ]);

        $transaction = Transaction::firstWhere('customer_name', 'Andi Bukti');
        $this->assertNotNull($transaction->proof_path);
        Storage::disk('public')->assertExists($transaction->proof_path);
    }

    public function test_web_orders_are_marked_as_website_source(): void
    {
        $transaction = $this->transaction();

        $this->assertSame('website', $transaction->source);
        $this->assertFalse($transaction->isManual());
    }
}
