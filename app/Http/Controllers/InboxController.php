<?php

namespace App\Http\Controllers;

use App\Models\IncomingChat;
use App\Models\ChatReply;
use App\Services\FonnteGatewayService;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $query = IncomingChat::with('farmer')->orderByDesc('updated_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('phone', 'like', "%{$search}%")
                  ->orWhereHas('farmer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $chats = $query->get();
        
        $activeChat = null;
        if ($request->has('chat')) {
            $activeChat = IncomingChat::with(['farmer', 'replies' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }])->find($request->chat);
            
            // Mark as read
            if ($activeChat && !$activeChat->is_read) {
                $activeChat->update(['is_read' => true]);
            }
        }

        return view('inbox.index', compact('chats', 'activeChat'));
    }

    public function reply(Request $request, IncomingChat $chat, FonnteGatewayService $fonnte)
    {
        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        // Save to DB
        ChatReply::create([
            'incoming_chat_id' => $chat->id,
            'sender_type' => 'user',
            'message' => $request->message
        ]);

        $chat->update([
            'last_message' => $request->message,
            'updated_at' => now()
        ]);

        // Send via Fonnte
        $fonnte->sendMessage($chat->phone, $request->message);

        return redirect()->route('inbox.index', ['chat' => $chat->id])->with('success', 'Balasan terkirim');
    }
}
