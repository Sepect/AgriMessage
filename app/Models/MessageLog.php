<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    protected $fillable = ['broadcast_id', 'farmer_id', 'phone', 'content', 'status', 'fonnte_id'];

    public function broadcast()
    {
        return $this->belongsTo(Broadcast::class);
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}
