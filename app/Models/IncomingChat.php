<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingChat extends Model
{
    protected $fillable = ['phone', 'farmer_id', 'last_message', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function replies()
    {
        return $this->hasMany(ChatReply::class);
    }
}
