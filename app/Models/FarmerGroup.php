<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerGroup extends Model
{
    protected $fillable = ['name', 'leader_id', 'region_id'];

    public function leader()
    {
        return $this->belongsTo(Farmer::class, 'leader_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function members()
    {
        return $this->belongsToMany(Farmer::class, 'farmer_group_members');
    }
}
