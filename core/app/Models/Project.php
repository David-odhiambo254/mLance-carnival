<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function projectActivities()
    {
        return $this->hasMany(ProjectActivity::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::PROJECT_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } elseif ($this->status == Status::PROJECT_ACCEPTED) {
                $html = '<span class="badge badge--success">' . trans('Accepted') . '</span>';
            } elseif ($this->status == Status::PROJECT_REJECTED) {
                $html = '<span class="badge badge--danger">' . trans('Rejected')  . '</span>';
            } elseif ($this->status == Status::PROJECT_REPORTED) {
                $html = '<span class="badge badge--warning">' . trans('Reported') . '</span>';
            } elseif ($this->status == Status::PROJECT_COMPLETED) {
                $html = '<span class="badge badge--success">' . trans('Completed')  . '</span>';
            }
            return $html;
        });
    }


    public function scopePending($query)
    {
        return $query->where('status', Status::PROJECT_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::PROJECT_REJECTED);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', Status::PROJECT_ACCEPTED);
    }

    public function scopeReported($query)
    {
        return $query->where('status', Status::PROJECT_REPORTED);
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', Status::PROJECT_COMPLETED);
    }
}
