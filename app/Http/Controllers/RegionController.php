<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $query = Region::with('parent')->withCount('farmers');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $regions = $query->paginate(15)->withQueryString();
        $kecamatans = Region::where('type', 'kecamatan')->get();
        return view('wilayah.index', compact('regions', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:kecamatan,desa',
            'parent_id' => 'nullable|exists:regions,id',
        ]);

        Region::create($validated);

        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil ditambahkan');
    }

    public function update(Request $request, Region $wilayah)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:kecamatan,desa',
            'parent_id' => 'nullable|exists:regions,id',
        ]);

        $wilayah->update($validated);

        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil diperbarui');
    }

    public function destroy(Region $wilayah)
    {
        $wilayah->delete();
        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil dihapus');
    }
}
