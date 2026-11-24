<?php

namespace Tests\Feature;

use App\Livewire\Admin\BannedWords;
use App\Livewire\Admin\Testimonials;
use App\Livewire\LandingPage\Components\TestimonialForm;
use App\Models\BannedWord;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TestimonialModerationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_banned_word_text_contains_is_case_insensitive(): void
    {
        BannedWord::create(['word' => 'penipu']);

        $this->assertTrue(BannedWord::textContains('Saya kena PENIPU sekali'));
        $this->assertTrue(BannedWord::textContains('penipu di awal'));
        $this->assertFalse(BannedWord::textContains('aman dan nyaman'));
        $this->assertFalse(BannedWord::textContains(''));
    }

    public function test_clean_testimonial_is_approved_immediately(): void
    {
        Livewire::test(TestimonialForm::class)
            ->set(['name' => 'Rina W.', 'rating' => '5', 'message' => 'Prosesnya sangat cepat dan memuaskan!'])
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('testimonials', [
            'name' => 'Rina W.',
            'status' => Testimonial::STATUS_APPROVED,
        ]);
    }

    public function test_testimonial_with_banned_word_requires_approval(): void
    {
        BannedWord::create(['word' => 'scam']);

        Livewire::test(TestimonialForm::class)
            ->set(['name' => 'Anon', 'message' => 'Mereka SCAM semua pelanggan, hati-hati!'])
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('pendingApproval', true)
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('testimonials', [
            'name' => 'Anon',
            'status' => Testimonial::STATUS_PENDING,
        ]);
    }

    public function test_only_approved_testimonials_are_displayed(): void
    {
        Testimonial::create(['name' => 'Andi', 'rating' => 5, 'message' => 'Ulasan bagus sekali.', 'status' => Testimonial::STATUS_APPROVED]);
        Testimonial::create(['name' => 'Budi', 'rating' => 1, 'message' => 'Ulasan buruk sekali.', 'status' => Testimonial::STATUS_PENDING]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Ulasan bagus sekali.')
            ->assertDontSee('Ulasan buruk sekali.');
    }

    public function test_testimonial_form_rejects_honeypot_and_invalid_input(): void
    {
        Livewire::test(TestimonialForm::class)
            ->set(['name' => '', 'rating' => '9', 'message' => 'ok', 'website' => 'spam.example'])
            ->call('submit')
            ->assertHasErrors(['name', 'rating', 'message', 'website']);

        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_admin_can_approve_and_delete_testimonial(): void
    {
        $testimonial = Testimonial::create([
            'name' => 'Anon',
            'rating' => 1,
            'message' => 'Pengalaman buruk.',
            'status' => Testimonial::STATUS_PENDING,
        ]);

        $this->actingAs($this->admin());

        $this->get('/admin/testimonials')
            ->assertStatus(200)
            ->assertSee('Anon');

        Livewire::test(Testimonials::class)
            ->call('approve', $testimonial->id);

        $this->assertDatabaseHas('testimonials', ['id' => $testimonial->id, 'status' => 'approved']);

        Livewire::test(Testimonials::class)
            ->call('hold', $testimonial->id);

        $this->assertDatabaseHas('testimonials', ['id' => $testimonial->id, 'status' => 'pending']);

        Livewire::test(Testimonials::class)
            ->call('delete', $testimonial->id);

        $this->assertSoftDeleted('testimonials', ['id' => $testimonial->id]);
    }

    public function test_admin_can_add_and_remove_banned_words(): void
    {
        $this->actingAs($this->admin());

        $this->get('/admin/banned-words')
            ->assertStatus(200);

        Livewire::test(BannedWords::class)
            ->set('word', 'Penipu')
            ->call('add')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('testimonial_banned_words', ['word' => 'penipu']);

        $word = BannedWord::firstOrFail();

        Livewire::test(BannedWords::class)
            ->call('delete', $word->id);

        $this->assertDatabaseMissing('testimonial_banned_words', ['id' => $word->id]);
    }

    public function test_banned_word_requires_input(): void
    {
        Livewire::test(BannedWords::class)
            ->set('word', '')
            ->call('add')
            ->assertHasErrors('word');

        $this->assertDatabaseCount('testimonial_banned_words', 0);
    }

    public function test_testimonials_page_can_filter_by_status(): void
    {
        Testimonial::create(['name' => 'Andi', 'rating' => 5, 'message' => 'Ulasan bagus sekali.', 'status' => Testimonial::STATUS_APPROVED]);
        Testimonial::create(['name' => 'Budi', 'rating' => 1, 'message' => 'Ulasan buruk sekali.', 'status' => Testimonial::STATUS_PENDING]);

        Livewire::test(Testimonials::class)
            ->set('status', 'pending')
            ->assertSee('Budi')
            ->assertDontSee('Andi');
    }
}
