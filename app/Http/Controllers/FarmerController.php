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
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group_id')) {
            $query->whereHas('groups', function ($q) use ($request) {
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
            'nik' => 'required|string|max:16|unique:farmers,nik,' . $petani->id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:farmers,phone,' . $petani->id,
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

    public function export(Request $request)
    {
        $farmers = Farmer::with(['region.parent', 'groups'])->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=data_petani_" . date('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['NIK', 'Nama', 'No WA', 'Kelompok Tani', 'Wilayah'];

        $callback = function () use ($farmers, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($farmers as $farmer) {
                fputcsv($file, [
                    $farmer->nik,
                    $farmer->name,
                    $farmer->phone,
                    $farmer->groups->first()->name ?? '',
                    $farmer->region ? $farmer->region->name : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function template()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=template_import_petani.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['NIK', 'Nama', 'No WA', 'Kelompok Tani', 'Wilayah'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // Add BOM for Excel UTF-8
            fputcsv($file, $columns);

            // Contoh baris
            fputcsv($file, ['7316012345678901', 'Budi Santoso', '081234567890', 'Maju Jaya', 'Balla']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file');

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            // Read headers
            $headers = fgetcsv($handle, 1000, ',');

            // If the file has BOM, strip it from the first column header
            if (isset($headers[0])) {
                $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);
            }

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Assuming format: NIK, Nama, No WA, Kelompok Tani, Wilayah
                if (count($data) < 3)
                    continue; // Skip invalid rows

                $nik = trim($data[0]);
                $name = trim($data[1]);
                $phone = trim($data[2]);
                $groupName = isset($data[3]) ? trim($data[3]) : null;
                $regionName = isset($data[4]) ? trim($data[4]) : null;

                if (empty($nik) || empty($name) || empty($phone)) {
                    continue; // Skip if basic required data is missing
                }

                $regionId = null;
                if (!empty($regionName)) {
                    $region = Region::where('name', 'like', "%{$regionName}%")->first();
                    if ($region) {
                        $regionId = $region->id;
                    }
                }

                $farmer = Farmer::updateOrCreate(
                    ['nik' => $nik],
                    [
                        'name' => $name,
                        'phone' => $phone,
                        'region_id' => $regionId,
                        'status' => 'active'
                    ]
                );

                if (!empty($groupName)) {
                    $group = FarmerGroup::where('name', 'like', "%{$groupName}%")->first();
                    if ($group) {
                        $farmer->groups()->syncWithoutDetaching([$group->id]);
                    }
                }
            }
            fclose($handle);
        }

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil diimpor.');
    }
}
