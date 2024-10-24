<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{

    protected $guarded=['id'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
