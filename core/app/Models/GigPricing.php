<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigPricing extends Model
{
    protected $casts = [
        'pricing_data' => 'object',
    ];
}
