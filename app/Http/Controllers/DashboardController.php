<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerGroup;
use App\Models\MessageLog;
use App\Models\IncomingChat;
use App\Models\Region;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPetani = Farmer::where('status', 'active')->count();
        $totalKelompok = FarmerGroup::count();
        $pesanTerkirim = MessageLog::whereIn('status', ['sent', 'delivered'])->count();
        $pesanGagal = MessageLog::where('status', 'failed')->count();

        // Tren Pengiriman Pesan (14 hari terakhir)
        $startDate = Carbon::now()->subDays(13)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $messageTrends = MessageLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['sent', 'delivered'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trendDates = [];
        $trendTotals = [];
        for ($i = 13; $i >= 0; $i--) {
            $dateStr = Carbon::now()->subDays($i)->format('Y-m-d');
            $trendDates[] = Carbon::parse($dateStr)->translatedFormat('d M');
            $match = $messageTrends->firstWhere('date', $dateStr);
            $trendTotals[] = $match ? $match->total : 0;
        }

        // Distribusi Wilayah Petani
        $regionDistribution = Farmer::select('region_id', DB::raw('count(*) as total'))
            ->whereNotNull('region_id')
            ->groupBy('region_id')
            ->with('region')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->region ? $item->region->name : 'Unknown',
                    'total' => $item->total
                ];
            });

        $regionLabels = $regionDistribution->pluck('name')->toArray();
        $regionData = $regionDistribution->pluck('total')->toArray();

        return view('dashboard', compact(
            'totalPetani',
            'totalKelompok',
            'pesanTerkirim',
            'pesanGagal',
            'trendDates',
            'trendTotals',
            'regionLabels',
            'regionData'
        ));
    }
}
