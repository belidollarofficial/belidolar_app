<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PayoutMethod extends Model
{
    public const TYPES = [
        'bank' => 'Bank',
        'ewallet' => 'E-Wallet',
    ];

    protected $guarded = [];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
