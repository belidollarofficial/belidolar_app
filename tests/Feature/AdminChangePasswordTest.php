<?php

namespace Tests\Feature;

use App\Livewire\Admin\ChangePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_change_password_page_requires_authentication(): void
    {
        $this->get('/admin/change-password')->assertRedirect('/login');
    }

    public function test_admin_can_open_change_password_page(): void
    {
        $this->actingAs($this->admin())->get('/admin/change-password')
            ->assertStatus(200)
            ->assertSee('Ubah Password');
    }

    public function test_admin_can_change_password_with_correct_current_password(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        Livewire::test(ChangePassword::class)
            ->set('current_password', 'secret123')
            ->set('password', 'newsecret456')
            ->set('password_confirmation', 'newsecret456')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('newsecret456', $admin->fresh()->password));
    }

    public function test_change_password_fails_with_wrong_current_password(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        Livewire::test(ChangePassword::class)
            ->set('current_password', 'salah-sekali')
            ->set('password', 'newsecret456')
            ->set('password_confirmation', 'newsecret456')
            ->call('update')
            ->assertHasErrors('current_password');

        $this->assertTrue(Hash::check('secret123', $admin->fresh()->password));
    }

    public function test_change_password_requires_matching_confirmation(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        Livewire::test(ChangePassword::class)
            ->set('current_password', 'secret123')
            ->set('password', 'newsecret456')
            ->set('password_confirmation', 'beda-sekali')
            ->call('update')
            ->assertHasErrors('password');

        $this->assertTrue(Hash::check('secret123', $admin->fresh()->password));
    }
}
