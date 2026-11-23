<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RateRange;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RateRangeController extends Controller
{
    public function index(): View
    {
        $ranges = RateRange::query()
            ->orderBy('min_amount')
            ->get();

        return view('admin.rate-ranges.index', compact('ranges'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        RateRange::create($data);

        return back()->with('success', 'Rate berhasil ditambahkan.');
    }

    public function edit(RateRange $rateRange): View
    {
        return view('admin.rate-ranges.edit', ['range' => $rateRange]);
    }

    public function update(Request $request, RateRange $rateRange): RedirectResponse
    {
        $data = $this->validateData($request, $rateRange);

        $rateRange->update($data);

        return redirect()
            ->route('admin.rate-ranges.index')
            ->with('success', 'Rate berhasil diperbarui.');
    }

    public function destroy(RateRange $rateRange): RedirectResponse
    {
        $rateRange->delete();

        return back()->with('success', 'Rate berhasil dihapus.');
    }

    protected function validateData(Request $request, ?RateRange $except = null): array
    {
        $data = $request->validate([
            'min_amount' => ['required', 'numeric', 'min:0'],
            'max_amount' => ['required', 'numeric', 'gt:min_amount'],
            'rate' => ['required', 'numeric', 'min:0'],
        ]);

        $overlap = RateRange::query()
            ->where('min_amount', '<=', $data['max_amount'])
            ->where('max_amount', '>=', $data['min_amount'])
            ->when($except, fn ($query) => $query->whereKeyNot($except->id))
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'min_amount' => 'Rentang ini tumpang tindih (overlap) dengan rentang lain.',
            ]);
        }

        return $data;
    }
}
