<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use GlobalStatus;

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::CATEGORY_FEATURED);
    }
    
    public function gigs()
    {
        return $this->hasMany(Gig::class);
    }
}
