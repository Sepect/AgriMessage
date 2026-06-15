<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Broadcast;
use App\Models\IncomingChat;
use App\Models\MessageLog;
use Illuminate\Pagination\LengthAwarePaginator;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'Semua Kategori');
        $tahun = $request->get('tahun', 'Semua Tahun');
        $search = $request->get('search', '');

        $items = collect();

        // 1. Fetch Broadcasts
        if ($kategori === 'Semua Kategori' || $kategori === 'Broadcast') {
            $broadcastsQuery = Broadcast::where('status', 'completed');

            if (!empty($search)) {
                $broadcastsQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }
            if ($tahun !== 'Semua Tahun') {
                $broadcastsQuery->whereYear('created_at', $tahun);
            }

            $broadcasts = $broadcastsQuery->get()->map(function ($b) {
                // Get recipient count
                $sentCount = MessageLog::where('broadcast_id', $b->id)->whereIn('status', ['sent', 'delivered'])->count();

                $targetName = 'Semua Wilayah';
                if (!empty($b->target_segment['type'])) {
                    if ($b->target_segment['type'] === 'region') {
                        $region = \App\Models\Region::find($b->target_segment['id']);
                        $targetName = $region ? $region->name : 'Wilayah';
                    } elseif ($b->target_segment['type'] === 'group') {
                        $group = \App\Models\FarmerGroup::find($b->target_segment['id']);
                        $targetName = $group ? $group->name : 'Kelompok';
                    }
                }

                return (object) [
                    'id' => $b->id,
                    'type' => 'Broadcast',
                    'title' => $b->title,
                    'content' => $b->content,
                    'target' => $targetName,
                    'target_detail' => number_format($sentCount) . ' Terkirim',
                    'date' => $b->created_at,
                ];
            });
            $items = $items->merge($broadcasts);
        }

        // 2. Fetch Incoming Chats (Pesan Personal)
        if ($kategori === 'Semua Kategori' || $kategori === 'Pesan Personal') {
            $chatsQuery = IncomingChat::with('farmer');

            if (!empty($search)) {
                $chatsQuery->whereHas('farmer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('last_message', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            }
            if ($tahun !== 'Semua Tahun') {
                $chatsQuery->whereYear('created_at', $tahun);
            }

            $chats = $chatsQuery->get()->map(function ($c) {
                return (object) [
                    'id' => $c->id,
                    'type' => 'Pesan Personal',
                    'title' => 'Pesan Personal',
                    'content' => $c->last_message,
                    'target' => $c->farmer ? $c->farmer->name : 'Anonim',
                    'target_detail' => $c->phone,
                    'date' => $c->created_at,
                ];
            });
            $items = $items->merge($chats);
        }

        // Sort by date descending
        $items = $items->sortByDesc('date')->values();

        // Paginate manually
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginator = new LengthAwarePaginator($currentItems, $items->count(), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('arsip.index', [
            'arsips' => $paginator,
            'kategori' => $kategori,
            'tahun' => $tahun,
            'search' => $search
        ]);
    }

    public function destroyAll()
    {
        // Delete all completed broadcasts and incoming chats
        Broadcast::where('status', 'completed')->delete();
        IncomingChat::query()->delete();
        // optionally delete MessageLog and ChatReply

        return redirect()->route('arsip.index')->with('success', 'Semua arsip berhasil dihapus.');
    }

    public function show($type, $id)
    {
        if ($type === 'broadcast') {
            $item = Broadcast::findOrFail($id);
            $logs = MessageLog::where('broadcast_id', $id)->get();
            return view('arsip.show-broadcast', compact('item', 'logs'));
        } elseif ($type === 'personal') {
            $item = IncomingChat::with('farmer', 'replies')->findOrFail($id);
            return view('arsip.show-personal', compact('item'));
        }
        abort(404);
    }

    public function destroy($type, $id)
    {
        if ($type === 'broadcast') {
            Broadcast::findOrFail($id)->delete();
        } elseif ($type === 'personal') {
            IncomingChat::findOrFail($id)->delete();
        }
        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil dihapus permanen.');
    }
}
