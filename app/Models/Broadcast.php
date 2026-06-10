<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $fillable = ['title', 'template_id', 'content', 'scheduled_at', 'status', 'target_segment'];

    protected $casts = [
        'target_segment' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function recipients()
    {
        return $this->hasMany(BroadcastRecipient::class);
    }
}
