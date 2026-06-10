<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = ['nik', 'name', 'phone', 'region_id', 'status'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function groups()
    {
        return $this->belongsToMany(FarmerGroup::class, 'farmer_group_members');
    }

    public function incomingChats()
    {
        return $this->hasMany(IncomingChat::class);
    }
}
