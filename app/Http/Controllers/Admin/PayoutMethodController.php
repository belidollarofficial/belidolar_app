<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PayoutMethodController extends Controller
{
    public function index(): View
    {
        $methods = PayoutMethod::query()
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('type');

        return view('admin.payout-methods.index', [
            'methods' => $methods,
            'types' => PayoutMethod::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        PayoutMethod::create($this->validateData($request));

        return back()->with('success', 'Metode pencairan berhasil ditambahkan.');
    }

    public function edit(PayoutMethod $payoutMethod): View
    {
        return view('admin.payout-methods.edit', [
            'method' => $payoutMethod,
            'types' => PayoutMethod::TYPES,
        ]);
    }

    public function update(Request $request, PayoutMethod $payoutMethod): RedirectResponse
    {
        $payoutMethod->update($this->validateData($request));

        return redirect()
            ->route('admin.payout-methods.index')
            ->with('success', 'Metode pencairan berhasil diperbarui.');
    }

    public function destroy(PayoutMethod $payoutMethod): RedirectResponse
    {
        $payoutMethod->delete();

        return back()->with('success', 'Metode pencairan berhasil dihapus.');
    }

    /**
     * @return array{type: string, name: string, sort_order: int}
     */
    protected function validateData(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(PayoutMethod::TYPES))],
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            'type' => $data['type'],
            'name' => $data['name'],
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
