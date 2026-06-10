<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatReply extends Model
{
    protected $fillable = ['incoming_chat_id', 'sender_type', 'message'];

    public function incomingChat()
    {
        return $this->belongsTo(IncomingChat::class);
    }
}
