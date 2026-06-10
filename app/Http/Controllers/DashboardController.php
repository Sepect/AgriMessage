<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerGroup;
use App\Models\MessageLog;
use App\Models\IncomingChat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPetani = Farmer::where('status', 'active')->count();
        $totalKelompok = FarmerGroup::count();
        $pesanTerkirim = MessageLog::whereIn('status', ['sent', 'delivered'])->count();
        $pesanGagal = MessageLog::where('status', 'failed')->count();

        return view('dashboard', compact('totalPetani', 'totalKelompok', 'pesanTerkirim', 'pesanGagal'));
    }
}
