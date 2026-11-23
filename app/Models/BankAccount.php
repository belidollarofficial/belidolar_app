<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Label untuk dropdown di form order, mis. "BCA • 1234567890".
     */
    public function getLabelAttribute(): string
    {
        return $this->bank_name.' • '.$this->account_number;
    }
}
