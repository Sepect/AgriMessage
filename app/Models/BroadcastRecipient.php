<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BroadcastRecipient extends Model
{
    protected $fillable = ['broadcast_id', 'farmer_id', 'status'];

    public function broadcast()
    {
        return $this->belongsTo(Broadcast::class);
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}
