<?php

namespace Tests\Feature;

use App\Livewire\Admin\TermsContentEditor;
use App\Livewire\LandingPage\Components\Terms;
use App\Models\Content;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Support\Branding;
use App\Support\SiteContent;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPagesTest extends TestCase
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

    public function test_landing_page_renders_content_from_database(): void
    {
        $this->seed(ContentSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Jasa Pembayaran Digital');
        $response->assertDontSee('Kurs Hari Ini');
        $response->assertDontSee('Online 24 Jam');
        $response->assertDontSee('x-data', false);
    }

    public function test_admin_routes_require_authentication(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
        $this->get('/admin/contents')->assertRedirect('/login');
        $this->get('/admin/settings')->assertRedirect('/login');
        $this->get('/admin/transactions')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        // Aplikasi ini tidak memakai MustVerifyEmail, jadi user tanpa verifikasi
        // tetap bisa mengakses halaman admin selama sudah login.
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)->get('/admin/dashboard')
            ->assertStatus(200);
    }

    public function test_dashboard_page_is_accessible(): void
    {
        $this->seed(ContentSeeder::class);
        $this->actingAs($this->admin())->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee('Manajemen User');
    }

    public function test_users_index_lists_users(): void
    {
        $this->seed(ContentSeeder::class);
        $existing = User::factory()->create(['name' => 'Andi Admin']);

        $this->actingAs($this->admin())->get('/admin/users')
            ->assertStatus(200)
            ->assertSee('Andi Admin')
            ->assertSee('Tambah User');
    }

    public function test_admin_can_create_user(): void
    {
        $this->seed(ContentSeeder::class);

        $response = $this->actingAs($this->admin())->post('/admin/users', [
            'name' => 'User Baru',
            'email' => 'userbaru@example.com',
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['email' => 'userbaru@example.com']);
    }

    public function test_admin_can_update_user(): void
    {
        $this->seed(ContentSeeder::class);
        $user = User::factory()->create(['name' => 'Lama', 'email' => 'lama@example.com']);

        $this->actingAs($this->admin())->put("/admin/users/{$user->id}", [
            'name' => 'Baru Lagi',
            'email' => 'baru@example.com',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Baru Lagi', 'email' => 'baru@example.com']);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $this->seed(ContentSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $this->seed(ContentSeeder::class);
        $target = User::factory()->create();

        $this->actingAs($this->admin())->delete("/admin/users/{$target->id}")
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_contents_page_shows_sections(): void
    {
        $this->seed(ContentSeeder::class);

        $this->actingAs($this->admin())->get('/admin/contents')
            ->assertStatus(200)
            ->assertSee('Hero Banner')
            ->assertSee('Testimoni')
            ->assertSee('Reset')
            ->assertSee('id="content-reset-', false)
            ->assertSee(route('admin.contents.reset', Content::where('section', 'hero')->where('key', 'title')->firstOrFail()), false);
    }

    public function test_admin_can_update_landing_content(): void
    {
        $this->seed(ContentSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)->put('/admin/contents', [
            'contents' => [
                'hero' => ['title' => 'Judul Admin Baru'],
            ],
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('contents', [
            'section' => 'hero',
            'key' => 'title',
            'value' => 'Judul Admin Baru',
        ]);
        $this->assertSame('Judul Admin Baru', SiteContent::get('hero', 'title'));
    }

    public function test_admin_can_reset_landing_content_to_default(): void
    {
        $this->seed(ContentSeeder::class);
        SiteContent::flush();

        $content = Content::where('section', 'hero')->where('key', 'title')->firstOrFail();
        $content->update(['value' => 'Judul Salah Edit']);
        SiteContent::flush();

        $this->assertSame('Judul Salah Edit', SiteContent::get('hero', 'title'));

        $this->actingAs($this->admin())
            ->put(route('admin.contents.reset', $content))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'value' => 'Jasa Pembayaran Digital',
        ]);
        $this->assertSame('Jasa Pembayaran Digital', SiteContent::get('hero', 'title'));
    }

    public function test_reset_landing_content_requires_authentication(): void
    {
        $this->seed(ContentSeeder::class);
        $content = Content::where('section', 'hero')->where('key', 'title')->firstOrFail();

        $this->put(route('admin.contents.reset', $content))->assertRedirect('/login');

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'value' => 'Jasa Pembayaran Digital',
        ]);
    }

    public function test_reset_landing_content_fails_when_default_is_missing(): void
    {
        $this->seed(ContentSeeder::class);

        $content = Content::create([
            'section' => 'hero',
            'key' => 'custom_field',
            'label' => 'Field Custom',
            'type' => 'text',
            'value' => 'Nilai manual',
            'sort_order' => 999,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.contents.reset', $content))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'value' => 'Nilai manual',
        ]);
    }

    public function test_landing_page_shows_new_stats_and_service_terms(): void
    {
        $this->seed(ContentSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('2K+')
            ->assertSee('★ 4.9')
            ->assertSee('data-target="36000"', false)
            ->assertSee('data-target="3000000"', false)
            ->assertSee('2021')
            ->assertSee('Pencairan akun permanen 180 hari')
            ->assertSee('Nominal top up menggunakan USD')
            ->assertDontSee('1. Penerimaan Syarat')
            ->assertDontSee('Ketentuan Jasa Bayar Online');
    }

    public function test_terms_card_follows_selected_service(): void
    {
        Livewire::test(Terms::class)
            ->assertSee('Nominal top up menggunakan USD')
            ->call('syncService', 'invoice')
            ->assertSee('Link invoice wajib valid')
            ->assertDontSee('Nominal top up menggunakan USD')
            ->call('syncService', 'wd_akun_180')
            ->assertSee('Layanan WD berlaku');
    }

    public function test_admin_can_edit_service_specific_terms(): void
    {
        $this->seed(ContentSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.contents.update'), [
            'contents' => [
                'syarat_ketentuan' => [
                    'invoice' => '<h3>Invoice Khusus</h3><p>Isi khusus.</p><script>alert(1)</script>',
                ],
            ],
        ])->assertSessionHas('success');

        SiteContent::flush();

        $this->get('/')->assertSee('Nominal top up menggunakan USD');
        Livewire::test(Terms::class)
            ->call('syncService', 'invoice')
            ->assertSee('Invoice Khusus')
            ->assertDontSee('alert(1)');
        $this->get(route('legal.terms'))->assertOk()->assertSee('Invoice Khusus');
    }

    public function test_terms_are_created_and_saved_if_service_content_row_is_missing(): void
    {
        $this->seed(ContentSeeder::class);
        $admin = $this->admin();

        Content::query()
            ->where('section', 'syarat_ketentuan')
            ->where('key', 'invoice')
            ->delete();

        $this->actingAs($admin)
            ->get('/admin/contents?terms_service=invoice')
            ->assertOk()
            ->assertSee('Bayar Invoice PayPal')
            ->assertSee('contenteditable="true"', false);

        $this->actingAs($admin)->put(route('admin.contents.update'), [
            'contents' => [
                'syarat_ketentuan' => [
                    'invoice' => '<p>Ketentuan invoice production.</p>',
                ],
            ],
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('contents', [
            'section' => 'syarat_ketentuan',
            'key' => 'invoice',
            'value' => '<p>Ketentuan invoice production.</p>',
        ]);
    }

    public function test_settings_page_shows_categories(): void
    {
        $this->seed(ContentSeeder::class);

        $this->actingAs($this->admin())->get('/admin/settings')
            ->assertStatus(200)
            ->assertSee('Informasi Umum')
            ->assertSee('Logo & Favicon')
            ->assertSee('Estimasi Fee Top Up')
            ->assertSee('Biaya Admin')
            ->assertDontSee('Kurs Hari Ini');
    }

    public function test_admin_can_upload_and_remove_branding_assets(): void
    {
        Storage::fake('public');
        $this->seed(ContentSeeder::class);
        $admin = $this->admin();

        // PNG 1x1 valid agar validasi mime berjalan tanpa ekstensi GD.
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

        $this->actingAs($admin)->put('/admin/settings', [
            'settings' => ['branding' => ['logo' => '', 'favicon' => '']],
            'files' => [
                'branding' => [
                    'logo' => UploadedFile::fake()->createWithContent('logo.png', $png),
                    'favicon' => UploadedFile::fake()->createWithContent('favicon.png', $png),
                ],
            ],
        ])->assertSessionHas('success');

        SiteContent::flush();

        $logoPath = Setting::query()->where('category', 'branding')->where('key', 'logo')->value('value');
        $faviconPath = Setting::query()->where('category', 'branding')->where('key', 'favicon')->value('value');

        $this->assertNotEmpty($logoPath);
        $this->assertNotEmpty($faviconPath);
        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($faviconPath);
        $this->assertStringContainsString($logoPath, Branding::logoUrl());
        $this->assertStringContainsString($faviconPath, Branding::faviconUrl());

        $this->actingAs($admin)->get('/admin/settings')
            ->assertOk()
            ->assertSee('name="remove_files[branding][logo]"', false)
            ->assertSee('name="remove_files[branding][favicon]"', false);

        $this->actingAs($admin)->put('/admin/settings', [
            'settings' => ['branding' => ['logo' => $logoPath, 'favicon' => $faviconPath]],
            'remove_files' => ['branding' => ['logo' => '1', 'favicon' => '1']],
        ])->assertSessionHas('success');

        Storage::disk('public')->assertMissing($logoPath);
        $this->assertSame('', Setting::query()->where('category', 'branding')->where('key', 'logo')->value('value'));
        SiteContent::flush();
        $this->assertStringContainsString('images/belidolar-logo.svg', Branding::logoUrl());
    }

    public function test_branding_upload_rejects_invalid_file_type(): void
    {
        Storage::fake('public');
        $this->seed(ContentSeeder::class);

        $this->actingAs($this->admin())->put('/admin/settings', [
            'settings' => ['branding' => ['logo' => '']],
            'files' => [
                'branding' => [
                    'logo' => UploadedFile::fake()->createWithContent('logo.php', '<?php echo "x";'),
                ],
            ],
        ])->assertSessionHasErrors('files.branding.logo');

        $this->assertSame('', Setting::query()->where('category', 'branding')->where('key', 'logo')->value('value'));
    }

    public function test_admin_can_update_fee_settings(): void
    {
        $this->seed(ContentSeeder::class);

        $this->actingAs($this->admin())->put('/admin/settings', [
            'settings' => [
                'fee' => ['min' => '8', 'max' => '20'],
            ],
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'category' => 'fee',
            'key' => 'min',
            'value' => '8',
        ]);
        $this->assertSame('8', SiteContent::setting('fee.min'));
        $this->assertSame('20', SiteContent::setting('fee.max'));
    }

    public function test_terms_admin_editor_can_switch_transaction_type(): void
    {
        $this->seed(ContentSeeder::class);
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/contents');

        $response->assertOk()
            ->assertSee('Jenis Transaksi')
            ->assertSee('Nominal top up menggunakan USD');

        $this->assertSame(1, substr_count($response->getContent(), 'contenteditable="true"'));

        Livewire::test(TermsContentEditor::class)
            ->set('service', 'invoice')
            ->assertSet('service', 'invoice')
            ->assertSee('Link invoice wajib valid');
    }

    public function test_landing_page_renders_editable_terms_and_conditions(): void
    {
        $this->seed(ContentSeeder::class);

        Content::query()
            ->where('section', 'syarat_ketentuan')
            ->where('key', 'topup')
            ->update([
                'value' => '<h2>Ketentuan Uji</h2><p>Isi ketentuan.</p><script>alert(1)</script>',
            ]);

        SiteContent::flush();

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('id="syarat-ketentuan"', false)
            ->assertSee('Ketentuan Uji')
            ->assertSee('Isi ketentuan.')
            ->assertDontSee('alert(1)');
    }

    public function test_admin_can_update_terms_content_with_wysiwyg_and_sanitization(): void
    {
        $this->seed(ContentSeeder::class);
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/contents');
        $response->assertStatus(200)
            ->assertSee('Jenis Transaksi')
            ->assertSee('Isi Syarat &amp; Ketentuan', false)
            ->assertSee('contenteditable="true"', false);

        $this->assertSame(1, substr_count($response->getContent(), 'contenteditable="true"'));

        Livewire::actingAs($admin)
            ->test(TermsContentEditor::class)
            ->set('content', '<h2>Poin Baru</h2><p>Aman.</p><script>alert(1)</script>')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $content = Content::query()
            ->where('section', 'syarat_ketentuan')
            ->where('key', 'topup')
            ->firstOrFail();

        $this->assertStringContainsString('Poin Baru', $content->value);
        $this->assertStringNotContainsString('<script>', $content->value);

        SiteContent::flush();

        $this->get('/')->assertSee('Poin Baru')->assertDontSee('alert(1)');
        $this->get(route('legal.terms'))->assertOk()->assertSee('Poin Baru');
    }

    public function test_new_admin_pages_require_authentication(): void
    {
        $this->get('/admin/testimonials')->assertRedirect('/login');
        $this->get('/admin/banned-words')->assertRedirect('/login');
        $this->get('/admin/reports')->assertRedirect('/login');
        $this->get('/admin/reports/export')->assertRedirect('/login');
        $this->get('/admin/bank-accounts')->assertRedirect('/login');
        $this->get('/admin/payout-methods')->assertRedirect('/login');
    }

    public function test_reports_page_shows_stats_and_filters(): void
    {
        Transaction::factory()->create(['service' => 'topup', 'amount' => 250000]);
        Transaction::factory()->create(['service' => 'convert_usd', 'amount' => 100]);

        $this->actingAs($this->admin())->get('/admin/reports?service=convert_usd')
            ->assertStatus(200)
            ->assertSee('$100.00')
            ->assertDontSee('Rp 250.000');
    }

    public function test_reports_export_returns_csv(): void
    {
        Transaction::factory()->create(['service' => 'topup', 'amount' => 250000, 'customer_name' => 'Dewi']);

        $this->actingAs($this->admin())->get('/admin/reports/export')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertSee('Kode,Nama,"No. WhatsApp",', false)
            ->assertSee('Dewi');
    }
}
