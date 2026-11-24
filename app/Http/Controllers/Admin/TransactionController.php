<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Support\WhatsAppMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $transactions = Transaction::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('transaction_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            })
            ->when($status !== '' && array_key_exists($status, Transaction::STATUSES), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.transactions.index', compact('transactions', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.transactions.create', [
            'services' => Transaction::SERVICES,
            'statuses' => Transaction::STATUSES,
        ]);
    }

    /**
     * Simpan transaksi yang diinput manual oleh admin (transaksi di luar website).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'account_type' => ['nullable', 'string', 'in:'.implode(',', array_keys(Transaction::ACCOUNT_TYPES))],
            'service' => ['required', 'string', 'in:'.implode(',', array_keys(Transaction::SERVICES))],
            'amount' => ['required', 'numeric', 'min:1'],
            'payable_amount' => ['nullable', 'numeric', 'min:0'],
            'fee_estimate' => ['nullable', 'string', 'max:50'],
            'payment_link' => ['nullable', 'string', 'url', 'max:500'],
            'payout_account_number' => ['nullable', 'string', 'max:100'],
            'merchant_name' => ['nullable', 'string', 'max:255'],
            'merchant_url' => ['nullable', 'string', 'url', 'max:500'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Transaction::STATUSES))],
            'proof' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $phone = WhatsAppMessage::normalizePhone($data['customer_phone']);

        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateCode(),
            'customer_name' => $data['customer_name'],
            'customer_phone' => $phone ?: $data['customer_phone'],
            'email' => $data['email'] ?? null,
            'account_type' => $data['account_type'] ?? null,
            'service' => $data['service'],
            'amount' => $data['amount'],
            'payable_amount' => $data['payable_amount'] ?? null,
            'fee_estimate' => $data['fee_estimate'] ?? null,
            'payment_link' => $data['payment_link'] ?? null,
            'payout_account_number' => $data['payout_account_number'] ?? null,
            'merchant_name' => $data['merchant_name'] ?? null,
            'merchant_url' => $data['merchant_url'] ?? null,
            'payment_method' => $data['payment_method'] ?? null,
            'notes' => $data['notes'] ?? null,
            'admin_note' => $data['admin_note'] ?? null,
            'status' => $data['status'],
            'source' => 'manual',
        ]);

        if ($request->hasFile('proof')) {
            $transaction->proof_path = $request->file('proof')->store('proofs', 'public');
            $transaction->save();
        }

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('success', 'Transaksi manual '.$transaction->transaction_code.' berhasil ditambahkan.');
    }

    public function show(Transaction $transaction): View
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Transaction::STATUSES))],
            'admin_note' => ['nullable', 'string', 'max:2000'],
            'proof' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $transaction->status = $data['status'];
        $transaction->admin_note = $data['admin_note'] ?? null;

        if ($request->hasFile('proof')) {
            if ($transaction->proof_path) {
                Storage::disk('public')->delete($transaction->proof_path);
            }

            $transaction->proof_path = $request->file('proof')->store('proofs', 'public');
        }

        $transaction->save();

        return back()->with('success', 'Transaksi '.$transaction->transaction_code.' berhasil diperbarui.');
    }

    /**
     * Tandai notifikasi WA telah dikirim oleh admin & kembalikan link wa.me
     * yang siap dibuka untuk mengirim pesan ke nomor pelanggan.
     */
    public function notify(Transaction $transaction): JsonResponse
    {
        if ($transaction->notified_at === null) {
            $transaction->notified_at = now();
            $transaction->save();
        }

        return response()->json([
            'success' => true,
            'link' => $transaction->notificationLink(),
        ]);
    }

    public function destroy(Request $request, Transaction $transaction): RedirectResponse
    {
        // Soft delete: data & bukti pembayaran tetap tersimpan untuk keperluan audit.
        $transaction->delete();

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi '.$transaction->transaction_code.' berhasil dihapus.');
    }
}
