<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPortfolio extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}