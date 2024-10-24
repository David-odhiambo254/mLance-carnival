<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    public function gigPricings()
    {
        return $this->hasMany(GigPricing::class);
    }

    public function gigPricing($gigId)
    {
        return $this->gigPricings->where('gig_id', $gigId)->first();
    }
}
