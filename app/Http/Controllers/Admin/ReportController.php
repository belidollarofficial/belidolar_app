<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    /**
     * Ekspor transaksi yang sedang difilter di halaman Laporan menjadi CSV.
     */
    public function export(Request $request): Response
    {
        $transactions = Transaction::query()
            ->when($request->query('from') !== null && $request->query('from') !== '', fn ($query) => $query->whereDate('created_at', '>=', $request->query('from')))
            ->when($request->query('to') !== null && $request->query('to') !== '', fn ($query) => $query->whereDate('created_at', '<=', $request->query('to')))
            ->when($request->query('service') !== null && array_key_exists($request->query('service'), Transaction::SERVICES), fn ($query) => $query->where('service', $request->query('service')))
            ->when($request->query('status') !== null && array_key_exists($request->query('status'), Transaction::STATUSES), fn ($query) => $query->where('status', $request->query('status')))
            ->latest()
            ->get();

        $handle = fopen('php://temp', 'w');

        fputcsv($handle, [
            'Kode', 'Nama', 'No. WhatsApp', 'Email', 'Layanan', 'Nominal', 'Dibayar (IDR)', 'Status', 'Link Pembayaran', 'Catatan', 'Tanggal',
        ]);

        foreach ($transactions as $t) {
            fputcsv($handle, [
                $t->transaction_code,
                $t->customer_name,
                $t->customer_phone,
                $t->email ?? '',
                $t->service_label,
                $t->amount_label,
                $t->payable_amount ?? '',
                $t->status_label,
                $t->payment_link ?? '',
                $t->notes ?? '',
                $t->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $filename = 'laporan-transaksi-'.now()->format('Ymd-His').'.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
