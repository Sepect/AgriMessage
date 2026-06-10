<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerGroup;
use App\Models\Region;
use Illuminate\Http\Request;

class FarmerGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmerGroup::with(['leader', 'region', 'members']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('leader', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $groups = $query->paginate(15)->withQueryString();
        $regions = Region::where('type', 'desa')->get();
        $farmers = Farmer::where('status', 'active')->get();
        
        return view('kelompok-tani.index', compact('groups', 'regions', 'farmers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'leader_id' => 'nullable|exists:farmers,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        FarmerGroup::create($validated);

        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani berhasil ditambahkan');
    }

    public function update(Request $request, FarmerGroup $kelompok_tani)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'leader_id' => 'nullable|exists:farmers,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        $kelompok_tani->update($validated);

        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani berhasil diperbarui');
    }

    public function destroy(FarmerGroup $kelompok_tani)
    {
        $kelompok_tani->delete();
        return redirect()->route('kelompok-tani.index')->with('success', 'Kelompok Tani berhasil dihapus');
    }
}
