<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use GlobalStatus;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::SUBCATEGORY_FEATURED);
    }
    public function scopePopular($query)
    {
        return $query->where('is_popular', Status::SUBCATEGORY_POPULAR);
    }

    public function gigs()
    {
        return $this->hasMany(Gig::class);
    }

    public function scopeHasGigs($query)
    {
        return $query->whereHas('gigs', function ($q) {
            $q->approved();
        });
    }
}
