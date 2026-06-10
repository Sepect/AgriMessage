<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerGroup;
use App\Models\Region;
use App\Repositories\FarmerRepository;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    protected $farmerRepo;

    public function __construct(FarmerRepository $farmerRepo)
    {
        $this->farmerRepo = $farmerRepo;
    }

    public function index(Request $request)
    {
        $query = Farmer::with(['region.parent', 'groups']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group_id')) {
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('farmer_groups.id', $request->group_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $farmers = $query->paginate(15)->withQueryString();
        $regions = Region::where('type', 'desa')->with('parent')->get();
        $groups = FarmerGroup::all();
        
        return view('petani.index', compact('farmers', 'regions', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:farmers,nik',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:farmers,phone',
            'region_id' => 'required|exists:regions,id',
            'group_id' => 'nullable|exists:farmer_groups,id'
        ]);

        $farmer = $this->farmerRepo->create($request->only('nik', 'name', 'phone', 'region_id'));

        if ($request->filled('group_id')) {
            $farmer->groups()->attach($request->group_id);
        }

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil ditambahkan');
    }

    public function update(Request $request, Farmer $petani)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:farmers,nik,'.$petani->id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:farmers,phone,'.$petani->id,
            'region_id' => 'required|exists:regions,id',
            'group_id' => 'nullable|exists:farmer_groups,id'
        ]);

        $this->farmerRepo->update($petani, $request->only('nik', 'name', 'phone', 'region_id'));

        if ($request->filled('group_id')) {
            $petani->groups()->sync([$request->group_id]);
        } else {
            $petani->groups()->detach();
        }

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil diperbarui');
    }

    public function destroy(Farmer $petani)
    {
        $this->farmerRepo->delete($petani);
        return redirect()->route('petani.index')->with('success', 'Data petani berhasil dihapus');
    }
}
