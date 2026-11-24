<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;

    public string $from = '';

    public string $to = '';

    public string $service = '';

    public string $status = '';

    protected $queryString = ['from', 'to', 'service', 'status'];

    public function resetFilters(): void
    {
        $this->reset('from', 'to', 'service', 'status');
    }

    private function baseQuery()
    {
        return Transaction::query()
            ->when($this->from !== '', fn ($query) => $query->whereDate('created_at', '>=', $this->from))
            ->when($this->to !== '', fn ($query) => $query->whereDate('created_at', '<=', $this->to))
            ->when($this->service !== '' && array_key_exists($this->service, Transaction::SERVICES), fn ($query) => $query->where('service', $this->service))
            ->when($this->status !== '' && array_key_exists($this->status, Transaction::STATUSES), fn ($query) => $query->where('status', $this->status));
    }

    public function getStatsProperty(): array
    {
        $query = $this->baseQuery();

        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
            'total_idr' => (clone $query)->whereIn('service', Transaction::PAYABLE_SERVICES)->sum('payable_amount'),
            'total_usd' => (clone $query)->where('service', 'convert_usd')->sum('amount'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.reports', [
            'stats' => $this->stats,
            'transactions' => $this->baseQuery()->latest()->paginate(15),
        ])->layout('layouts.admin', ['title' => 'Laporan']);
    }
}
