<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateRange extends Model
{
    protected $guarded = [];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * Cari rate (Rp/USD) untuk nominal tertentu.
     *
     * Nominal desimal dibulatkan ke bawah (floor) ke dollar utuh sebelum dicocokkan dengan
     * bracket, mis. $14,9 mengikuti rate bracket $14. Nominal di bawah min_amount bracket
     * pertama atau di atas max_amount bracket terakhir mengembalikan null.
     */
    public static function rateFor(float $amount): ?RateRange
    {
        if ($amount <= 0) {
            return null;
        }

        $amount = floor($amount);

        foreach (static::query()->orderBy('min_amount')->get() as $range) {
            if ($amount < $range->min_amount) {
                return null;
            }

            if ($amount <= $range->max_amount) {
                return $range;
            }
        }

        return null;
    }
}
