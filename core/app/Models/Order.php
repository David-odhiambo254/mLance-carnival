<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }
   
    public function scopePending($query)
    {
        return $query->where('status', Status::ORDER_PENDING);
    }
    public function scopeAccepted($query)
    {
        return $query->where('status', Status::ORDER_ACCEPTED);
    }
    public function scopeRejected($query)
    {
        return $query->where('status', Status::ORDER_REJECTED);
    }
    public function scopeReported($query)
    {
        return $query->where('status', Status::ORDER_REPORTED);
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', Status::ORDER_COMPLETED);
    }
}
