<?php

namespace App\Livewire\Admin;

use App\Models\Testimonial;
use Livewire\Component;
use Livewire\WithPagination;

class Testimonials extends Component
{
    use WithPagination;

    public string $status = '';

    public function mount(): void
    {
        $this->status = (string) request()->query('status', '');
    }

    public function approve(int $id): void
    {
        Testimonial::query()->whereKey($id)->update(['status' => Testimonial::STATUS_APPROVED]);

        $this->dispatch('toast', type: 'success', message: 'Testimoni disetujui dan kini tampil.');
    }

    public function hold(int $id): void
    {
        Testimonial::query()->whereKey($id)->update(['status' => Testimonial::STATUS_PENDING]);

        $this->dispatch('toast', type: 'warning', message: 'Testimoni ditarik dari halaman.');
    }

    public function delete(int $id): void
    {
        Testimonial::query()->whereKey($id)->delete();

        $this->dispatch('toast', type: 'success', message: 'Testimoni dihapus.');
    }

    public function render()
    {
        $testimonials = Testimonial::query()
            ->when(in_array($this->status, [Testimonial::STATUS_PENDING, Testimonial::STATUS_APPROVED], true), fn ($query) => $query->where('status', $this->status))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.testimonials', ['testimonials' => $testimonials])
            ->layout('layouts.admin', ['title' => 'Testimoni']);
    }
}
