<?php

namespace Tests\Feature;

use App\Livewire\LandingPage\Components\OrderForm;
use App\Models\RateRange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RateRangeTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_rate_for_returns_matching_range(): void
    {
        RateRange::create(['min_amount' => 11, 'max_amount' => 14, 'rate' => 13000]);
        RateRange::create(['min_amount' => 15, 'max_amount' => 17, 'rate' => 13500]);

        $this->assertSame(13000.0, (float) RateRange::rateFor(14)->rate);
        $this->assertSame(13500.0, (float) RateRange::rateFor(15)->rate);
        $this->assertSame(13500.0, (float) RateRange::rateFor(17)->rate);
        $this->assertNull(RateRange::rateFor(10));
        $this->assertNull(RateRange::rateFor(0.5));
        $this->assertNull(RateRange::rateFor(25));
    }

    public function test_rate_for_floors_decimal_to_lower_bracket(): void
    {
        RateRange::create(['min_amount' => 11, 'max_amount' => 14, 'rate' => 13000]);
        RateRange::create(['min_amount' => 15, 'max_amount' => 17, 'rate' => 13500]);

        $this->assertSame(13000.0, (float) RateRange::rateFor(14.9)->rate);
        $this->assertSame(13000.0, (float) RateRange::rateFor(14.99)->rate);
        $this->assertSame(13500.0, (float) RateRange::rateFor(17.1)->rate);
        $this->assertNull(RateRange::rateFor(18));
    }

    public function test_admin_can_create_rate_range(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.rate-ranges.store'), [
            'min_amount' => 11,
            'max_amount' => 14,
            'rate' => 13000,
        ])->assertRedirect();

        $this->assertDatabaseHas('rate_ranges', [
            'min_amount' => 11,
            'max_amount' => 14,
            'rate' => 13000,
        ]);
    }

    public function test_admin_can_update_rate_range(): void
    {
        $this->actingAs($this->admin());

        $range = RateRange::create(['min_amount' => 11, 'max_amount' => 14, 'rate' => 13000]);

        $this->put(route('admin.rate-ranges.update', $range), [
            'min_amount' => 11,
            'max_amount' => 17,
            'rate' => 13500,
        ])->assertRedirect(route('admin.rate-ranges.index'));

        $this->assertDatabaseHas('rate_ranges', [
            'id' => $range->id,
            'max_amount' => 17,
            'rate' => 13500,
        ]);
    }

    public function test_rate_range_should_not_overlap(): void
    {
        $this->actingAs($this->admin());

        RateRange::create(['min_amount' => 11, 'max_amount' => 14, 'rate' => 13000]);

        $this->from(route('admin.rate-ranges.index'))
            ->post(route('admin.rate-ranges.store'), [
                'min_amount' => 12,
                'max_amount' => 16,
                'rate' => 13500,
            ])
            ->assertSessionHasErrors('min_amount');

        $this->assertSame(1, RateRange::count());
    }

    public function test_admin_can_delete_rate_range(): void
    {
        $this->actingAs($this->admin());

        $range = RateRange::create(['min_amount' => 11, 'max_amount' => 14, 'rate' => 13000]);

        $this->delete(route('admin.rate-ranges.destroy', $range))->assertRedirect();

        $this->assertDatabaseMissing('rate_ranges', ['id' => $range->id]);
    }

    public function test_landing_order_form_shows_range_rate_for_convert_usd(): void
    {
        RateRange::create(['min_amount' => 11, 'max_amount' => 14, 'rate' => 13000]);
        RateRange::create(['min_amount' => 15, 'max_amount' => 17, 'rate' => 13500]);

        Livewire::test(OrderForm::class)
            ->set(['service' => 'convert_usd', 'amount' => '14.9'])
            ->assertSee('Rp 13.000/USD')
            ->assertSee('$11 - $14')
            ->assertSee('Estimasi diterima')
            ->assertSee('Rp 193.700');

        $this->get('/')
            ->assertOk()
            ->assertSee('Order via WhatsApp', false);
    }

    public function test_landing_order_form_shows_single_rate_for_topup(): void
    {
        RateRange::create(['min_amount' => 11, 'max_amount' => 14, 'rate' => 13000]);

        Livewire::test(OrderForm::class)
            ->set(['service' => 'topup', 'amount' => '50'])
            ->assertSee('Rp 18.300/USD')
            ->assertSee('Yang harus dibayar')
            ->assertSee('Rp 915.000');

        Livewire::test(OrderForm::class)
            ->set(['service' => 'invoice', 'amount' => '150'])
            ->assertSee('Rp 18.300/USD')
            ->assertSee('Rp 2.745.000');
    }
}
