<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    use  GlobalStatus;

    protected $casts = [
        'tags'     => 'object',
        'faqs'     => 'object',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function images()
    {
        return $this->hasMany(GigImage::class);
    }

    public function gigPricing()
    {
        return $this->hasMany(GigPricing::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    //Scope
    public function scopePending($query)
    {
        return $query->where('status', Status::GIG_PENDING);
    }
    public function scopeApproved($query)
    {
        return $query->where('status', Status::GIG_APPROVED)->where('is_published', Status::GIG_PUBLISHED);
    }
    public function scopeRejected($query)
    {
        return $query->where('status', Status::GIG_REJECTED);
    }



    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::GIG_APPROVED) {
                $html = '<span class="badge badge--success">' . trans("Approved") . '</span>';
            } elseif ($this->status == Status::GIG_PENDING) {
                $html = '<span class="badge badge--warning">' . trans("Pending") . '</span>';
            } elseif ($this->status == Status::GIG_REJECTED) {
                $html = '<span class="badge badge--danger">' . trans("Rejected") . '</span>';
            }
            return $html;
        });
    }

    public function publishingStatusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->is_published == Status::GIG_PUBLISHED) {
                $html = '<span class="badge badge--success">' . trans("Yes") . '</span>';
            } elseif ($this->is_published == Status::GIG_DRAFT) {
                $html = '<span class="badge badge--warning">' . trans("Draft") . '</span>';
            }
            return $html;
        });
    }
}
