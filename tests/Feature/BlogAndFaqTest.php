<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Post;
use App\Models\User;
use App\Support\HtmlSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogAndFaqTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('secret123'),
        ]);
    }

    private function makePost(array $overrides = []): Post
    {
        return Post::create(array_merge([
            'title' => 'Tips Top Up PayPal',
            'content' => '<p>Isi artikel yang cukup panjang untuk dibaca.</p>',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ], $overrides));
    }

    public function test_blog_index_only_lists_published_posts(): void
    {
        $this->makePost(['title' => 'Artikel Publik']);
        $this->makePost(['title' => 'Artikel Draft', 'is_published' => false]);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Artikel Publik')
            ->assertDontSee('Artikel Draft');
    }

    public function test_draft_post_is_not_accessible_to_public(): void
    {
        $post = $this->makePost(['is_published' => false]);

        $this->get('/blog/'.$post->slug)->assertNotFound();
    }

    public function test_blog_post_page_renders_sanitized_html(): void
    {
        $post = $this->makePost([
            'content' => '<h2>Judul Artikel</h2><p>Halo <strong>dunia</strong>.</p>'
                .'<script>alert("xss")</script>'
                .'<a href="javascript:alert(1)">klik</a>',
        ]);

        $this->get('/blog/'.$post->slug)
            ->assertOk()
            ->assertSee('Judul Artikel')
            ->assertSee('<strong>dunia</strong>', false)
            ->assertDontSee('alert("xss")', false)
            ->assertDontSee('javascript:alert(1)', false);
    }

    public function test_html_sanitizer_removes_dangerous_content(): void
    {
        $clean = HtmlSanitizer::clean(
            '<p onclick="evil()">Aman <a href="javascript:alert(1)">tautan</a></p>'
            .'<iframe src="https://evil.example"></iframe>'
            .'<img src="x" onerror="alert(1)">'
        );

        $this->assertStringContainsString('Aman', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
        $this->assertStringNotContainsString('<iframe', $clean);
        $this->assertStringNotContainsString('onerror', $clean);
    }

    public function test_post_generates_unique_slugs_and_sanitizes_on_save(): void
    {
        $first = $this->makePost(['content' => '<p>Aman</p><script>alert(1)</script>']);
        $second = $this->makePost();

        $this->assertNotSame($first->slug, $second->slug);
        $this->assertStringNotContainsString('<script>', $first->fresh()->content);
    }

    public function test_admin_can_create_update_and_delete_a_post(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Artikel Baru',
            'excerpt' => 'Ringkasan artikel',
            'content' => '<p>Konten <strong>baru</strong>.</p><script>alert(1)</script>',
            'is_published' => '1',
        ])->assertRedirect(route('admin.posts.index'));

        $post = Post::where('title', 'Artikel Baru')->firstOrFail();

        $this->assertSame('artikel-baru', $post->slug);
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->published_at);
        $this->assertStringNotContainsString('<script>', $post->content);

        $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title' => 'Artikel Diperbarui',
            'content' => '<p>Konten diperbarui.</p>',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Artikel Diperbarui',
            'is_published' => false,
        ]);

        // Slug berubah mengikuti judul, jadi ambil instance terbaru sebelum hapus.
        $post->refresh();

        $this->actingAs($admin)->delete(route('admin.posts.destroy', $post))
            ->assertRedirect();

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_admin_post_routes_require_authentication(): void
    {
        $this->get('/admin/posts')->assertRedirect('/login');
        $this->get('/admin/posts/create')->assertRedirect('/login');
    }

    public function test_active_faqs_appear_on_landing_page_accordion(): void
    {
        Faq::create([
            'question' => 'Berapa lama proses top up?',
            'answer' => 'Sekitar 20 menit.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Faq::create([
            'question' => 'Pertanyaan nonaktif?',
            'answer' => 'Tidak boleh tampil.',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $response = $this->get('/')
            ->assertOk()
            ->assertSee('<details', false)
            ->assertSee('Berapa lama proses top up?')
            ->assertSee('Sekitar 20 menit.')
            ->assertDontSee('Pertanyaan nonaktif?');

        $html = $response->getContent();
        $termsPosition = strpos($html, 'id="syarat-ketentuan"');
        $faqPosition = strpos($html, 'id="faq"');

        $this->assertNotFalse($termsPosition);
        $this->assertNotFalse($faqPosition);
        $this->assertGreaterThan($termsPosition, $faqPosition);
    }

    public function test_admin_can_manage_faqs(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.faqs.store'), [
            'question' => 'Apakah aman?',
            'answer' => 'Aman dan terpercaya.',
            'sort_order' => 3,
            'is_active' => '1',
        ])->assertRedirect();

        $faq = Faq::where('question', 'Apakah aman?')->firstOrFail();
        $this->assertTrue($faq->is_active);

        $this->actingAs($admin)->put(route('admin.faqs.update', $faq), [
            'question' => 'Apakah benar aman?',
            'answer' => 'Ya, aman.',
            'sort_order' => 1,
        ])->assertRedirect(route('admin.faqs.index'));

        $faq->refresh();
        $this->assertSame('Apakah benar aman?', $faq->question);
        $this->assertFalse($faq->is_active);

        $this->actingAs($admin)->delete(route('admin.faqs.destroy', $faq))->assertRedirect();

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_faq_admin_routes_require_authentication(): void
    {
        $this->get('/admin/faqs')->assertRedirect('/login');
    }
}
