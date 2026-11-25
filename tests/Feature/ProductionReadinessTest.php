<?php

namespace Tests\Feature;

use App\Livewire\LandingPage\Components\OrderForm;
use App\Livewire\LandingPage\Components\TestimonialForm;
use App\Livewire\LandingPage\Components\TransactionTracking;
use App\Models\Content;
use App\Models\Testimonial;
use App\Models\Transaction;
use App\Models\User;
use App\Support\SiteContent;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteContent::flush();
    }

    public function test_responses_include_security_headers(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Content-Security-Policy');
    }

    public function test_legal_pages_are_publicly_accessible(): void
    {
        $this->get(route('legal.about'))->assertOk()->assertSee('Tentang Kami');
        $this->get(route('legal.how-to-order'))->assertOk()->assertSee('Cara Order');
        $this->get(route('legal.terms'))->assertOk()->assertSee('Syarat & Ketentuan');
        $this->get(route('legal.privacy'))->assertOk()->assertSee('Kebijakan Privasi');
    }

    public function test_public_storage_files_are_served_without_symlink(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('branding/logo.png', 'png-bytes');

        $this->get('/storage/branding/logo.png')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $this->get('/storage/branding/missing.png')->assertNotFound();
    }

    public function test_footer_links_to_legal_pages(): void
    {
        $this->seed(ContentSeeder::class);

        Content::query()->updateOrCreate(
            ['section' => 'footer', 'key' => 'social_facebook_url'],
            ['label' => 'URL Profil Facebook', 'type' => 'text', 'value' => 'https://facebook.com/belidolar'],
        );
        SiteContent::flush();

        $this->get('/')
            ->assertOk()
            ->assertSee(route('legal.about'))
            ->assertSee(route('legal.terms'))
            ->assertSee(route('legal.privacy'))
            ->assertSee(route('legal.how-to-order'))
            ->assertSee(route('beranda').'?service=wd_akun_180#form-order')
            ->assertSee('href="https://facebook.com/belidolar"', false)
            ->assertSee('aria-label="Facebook"', false)
            ->assertDontSee('href="#"', false);
    }

    public function test_order_form_service_can_be_preselected_from_query_string(): void
    {
        Livewire::withQueryParams(['service' => 'invoice'])
            ->test(OrderForm::class)
            ->assertSet('service', 'invoice');
    }

    public function test_robots_and_sitemap_are_served(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee(route('sitemap'));

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<urlset', false)
            ->assertSee(route('legal.terms'));
    }

    public function test_admin_seeder_creates_a_single_verified_admin(): void
    {
        $this->seed(AdminSeeder::class);
        $this->seed(AdminSeeder::class);

        $admins = User::where('email', 'su-zonapay@gmail.com')->get();

        $this->assertCount(1, $admins);
        $this->assertTrue(Hash::check('adminadmin', $admins->first()->password));
        $this->assertNotNull($admins->first()->email_verified_at);
    }

    public function test_transactions_and_testimonials_use_soft_deletes(): void
    {
        $transaction = Transaction::factory()->create();
        $transaction->delete();

        $this->assertSoftDeleted('transactions', ['id' => $transaction->id]);
        $this->assertSame(0, Transaction::count());
        $this->assertSame(1, Transaction::withTrashed()->count());

        $testimonial = Testimonial::create([
            'name' => 'Siti',
            'rating' => 5,
            'message' => 'Pelayanan cepat dan ramah sekali.',
            'status' => Testimonial::STATUS_APPROVED,
        ]);
        $testimonial->delete();

        $this->assertSoftDeleted('testimonials', ['id' => $testimonial->id]);
        $this->assertSame(0, Testimonial::approved()->count());
        $this->assertSame(1, Testimonial::withTrashed()->count());
    }

    public function test_testimonial_submission_is_rate_limited_per_ip(): void
    {
        $key = 'testimonial:127.0.0.1';

        for ($i = 0; $i < 5; $i++) {
            RateLimiter::hit($key, 60);
        }

        Livewire::test(TestimonialForm::class)
            ->set(['name' => 'Rina', 'rating' => '5', 'message' => 'Prosesnya sangat cepat dan memuaskan!'])
            ->call('submit')
            ->assertHasErrors('form');

        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_transaction_tracking_is_rate_limited_per_ip(): void
    {
        $key = 'tracking:127.0.0.1';

        for ($i = 0; $i < 20; $i++) {
            RateLimiter::hit($key, 60);
        }

        Livewire::test(TransactionTracking::class)
            ->set('code', 'ZP-260609-AB12C')
            ->set('phone', '081290859343')
            ->call('search')
            ->assertHasErrors('code');
    }
}
