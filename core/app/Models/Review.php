<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function gig()
{
    return $this->belongsTo(Gig::class, 'gig_id');
}
}
