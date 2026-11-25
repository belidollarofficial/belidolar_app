<?php

namespace App\Livewire\Admin;

use App\Models\Content;
use App\Models\Transaction;
use App\Support\ContentDefaults;
use App\Support\HtmlSanitizer;
use App\Support\SiteContent;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TermsContentEditor extends Component
{
    public string $service = 'topup';

    public string $content = '';

    public function mount(): void
    {
        $this->loadSelectedContent();
    }

    public function updatedService(): void
    {
        if (! array_key_exists($this->service, Transaction::SERVICES)) {
            $this->service = 'topup';
        }

        $this->resetValidation('content');
        $this->loadSelectedContent();

        $this->dispatch('terms-editor-content-loaded', content: $this->content);
    }

    public function save(): void
    {
        $data = $this->validate([
            'service' => ['required', 'string', Rule::in(array_keys(Transaction::SERVICES))],
            'content' => ['required', 'string', 'max:200000'],
        ], [
            'content.required' => 'Isi syarat & ketentuan tidak boleh kosong.',
            'content.max' => 'Isi syarat & ketentuan terlalu panjang.',
        ]);

        $default = ContentDefaults::find('syarat_ketentuan', $data['service']);

        if ($default === null) {
            $this->addError('service', 'Jenis transaksi tidak memiliki konten default.');

            return;
        }

        $content = Content::query()->firstOrCreate(
            ['section' => 'syarat_ketentuan', 'key' => $data['service']],
            [
                'label' => $default['label'],
                'type' => $default['type'],
                'value' => $default['value'],
                'sort_order' => (int) Content::max('sort_order') + 1,
            ],
        );

        $this->content = HtmlSanitizer::clean($data['content']);
        $content->update(['value' => $this->content]);

        SiteContent::flush();
        $this->dispatch('terms-editor-content-loaded', content: $this->content);
        $this->dispatch('toast', type: 'success', message: 'Syarat & Ketentuan berhasil disimpan.');
    }

    public function resetToDefault(): void
    {
        $default = ContentDefaults::find('syarat_ketentuan', $this->service);

        if ($default === null) {
            $this->addError('service', 'Jenis transaksi tidak memiliki nilai default.');

            return;
        }

        $content = Content::query()->firstOrCreate(
            ['section' => 'syarat_ketentuan', 'key' => $this->service],
            [
                'label' => $default['label'],
                'type' => $default['type'],
                'value' => $default['value'],
                'sort_order' => (int) Content::max('sort_order') + 1,
            ],
        );

        $this->content = $default['value'];
        $content->update(['value' => $this->content]);

        SiteContent::flush();
        $this->resetValidation('content');
        $this->dispatch('terms-editor-content-loaded', content: $this->content);
        $this->dispatch('toast', type: 'success', message: 'Syarat & Ketentuan dikembalikan ke nilai default.');
    }

    private function loadSelectedContent(): void
    {
        $content = Content::query()
            ->where('section', 'syarat_ketentuan')
            ->where('key', $this->service)
            ->first();

        $this->content = $content?->value
            ?? ContentDefaults::value('syarat_ketentuan', $this->service)
            ?? '';
    }

    public function render()
    {
        return view('livewire.admin.terms-content-editor', [
            'services' => Transaction::SERVICES,
            'defaultContent' => ContentDefaults::value('syarat_ketentuan', $this->service),
        ]);
    }
}
