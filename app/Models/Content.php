<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $guarded = [];

    public $timestamps = true;

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
