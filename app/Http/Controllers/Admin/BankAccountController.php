<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankAccountController extends Controller
{
    public function index(): View
    {
        $accounts = BankAccount::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.bank-accounts.index', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        BankAccount::create($this->validateData($request));

        return back()->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    public function edit(BankAccount $bankAccount): View
    {
        return view('admin.bank-accounts.edit', ['account' => $bankAccount]);
    }

    public function update(Request $request, BankAccount $bankAccount): RedirectResponse
    {
        $bankAccount->update($this->validateData($request));

        return redirect()
            ->route('admin.bank-accounts.index')
            ->with('success', 'Rekening bank berhasil diperbarui.');
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        $bankAccount->delete();

        return back()->with('success', 'Rekening bank berhasil dihapus.');
    }

    /**
     * @return array{bank_name: string, account_name: string, account_number: string, sort_order: int}
     */
    protected function validateData(Request $request): array
    {
        $data = $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_name' => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            'bank_name' => $data['bank_name'],
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
