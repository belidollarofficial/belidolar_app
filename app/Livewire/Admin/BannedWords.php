<?php

namespace App\Livewire\Admin;

use App\Models\BannedWord;
use Livewire\Component;

class BannedWords extends Component
{
    public string $word = '';

    public function add(): void
    {
        $this->validate([
            'word' => ['required', 'string', 'max:50'],
        ]);

        $word = BannedWord::firstOrCreate(['word' => mb_strtolower(trim($this->word))]);

        $this->reset('word');

        $this->dispatch('toast', type: 'success', message: 'Kata terlarang "'.$word->word.'" ditambahkan.');
    }

    public function delete(int $id): void
    {
        BannedWord::query()->whereKey($id)->delete();

        $this->dispatch('toast', type: 'success', message: 'Kata terlarang dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.banned-words', [
            'words' => BannedWord::query()->orderBy('word')->get(),
        ])->layout('layouts.admin', ['title' => 'Filter Kata']);
    }
}
