<?php

namespace App\Http\Controllers;

use App\Models\Broadcast;
use App\Models\Template;
use App\Models\Region;
use App\Models\FarmerGroup;
use App\Services\BroadcastService;
use App\Models\MessageLog;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    protected $broadcastService;

    public function __construct(BroadcastService $broadcastService)
    {
        $this->broadcastService = $broadcastService;
    }

    public function index(Request $request)
    {
        $query = Broadcast::with('template')->withCount('recipients');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $broadcasts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $broadcasts->getCollection()->transform(function ($broadcast) {
            if ($broadcast->status == 'completed') {
                $broadcast->progress_percentage = 100;
            } elseif ($broadcast->status == 'draft') {
                $broadcast->progress_percentage = 0;
            } else {
                $total = \App\Models\MessageLog::where('broadcast_id', $broadcast->id)->count();
                if ($total > 0) {
                    $processed = \App\Models\MessageLog::where('broadcast_id', $broadcast->id)
                        ->whereIn('status', ['sent', 'delivered', 'failed'])
                        ->count();
                    $broadcast->progress_percentage = round(($processed / $total) * 100);
                    
                    // Auto-heal status if all messages are processed but status is still processing
                    if ($processed === $total && $broadcast->status !== 'completed') {
                        \App\Models\Broadcast::where('id', $broadcast->id)->update(['status' => 'completed']);
                        $broadcast->status = 'completed';
                    }
                } else {
                    $broadcast->progress_percentage = 0;
                }
            }
            return $broadcast;
        });
        
        $pesanTerkirim = MessageLog::whereIn('status', ['sent', 'delivered'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $pesanGagal = MessageLog::where('status', 'failed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('broadcast.index', compact('broadcasts', 'pesanTerkirim', 'pesanGagal'));
    }

    public function create()
    {
        $templates = Template::all();
        $regions = Region::where('type', 'desa')->get();
        $groups = FarmerGroup::all();
        return view('broadcast.create', compact('templates', 'regions', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'template_id' => 'nullable|exists:templates,id',
            'content' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'target_type' => 'required|in:all,region,group',
            'target_id' => 'nullable|integer',
        ]);

        $segment = [
            'type' => $request->target_type,
            'id' => $request->target_id
        ];

        $broadcast = Broadcast::create([
            'title' => $request->title,
            'template_id' => $request->template_id,
            'content' => $request->content,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'draft',
            'target_segment' => $segment
        ]);

        // If not scheduled for later (or scheduled now), we process it
        // Or if scheduled, we can process it which queues jobs with delays.
        $this->broadcastService->processBroadcast($broadcast);

        return redirect()->route('broadcast.index')->with('success', 'Broadcast berhasil dibuat dan sedang diproses');
    }
}
