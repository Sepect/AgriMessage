<?php

namespace App\Repositories;

use App\Models\Farmer;

class FarmerRepository
{
    public function getAllActive()
    {
        return Farmer::where('status', 'active')->get();
    }

    public function getByRegion($regionId)
    {
        return Farmer::where('region_id', $regionId)
            ->where('status', 'active')
            ->get();
    }

    public function getByGroup($groupId)
    {
        return Farmer::whereHas('groups', function($q) use ($groupId) {
            $q->where('farmer_groups.id', $groupId);
        })->where('status', 'active')->get();
    }

    public function create(array $data)
    {
        return Farmer::create($data);
    }

    public function update(Farmer $farmer, array $data)
    {
        $farmer->update($data);
        return $farmer;
    }

    public function delete(Farmer $farmer)
    {
        return $farmer->delete();
    }
}
