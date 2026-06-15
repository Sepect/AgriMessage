<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncomingChat;
use App\Models\ChatReply;
use Illuminate\Http\Request;

class InboxApiController extends Controller
{
    /**
     * Get recent messages for a specific chat since a given timestamp or ID.
     */
    public function getUpdates(Request $request, $chatId)
    {
        $chat = IncomingChat::with('farmer')->findOrFail($chatId);

        $query = ChatReply::where('incoming_chat_id', $chatId)->orderBy('created_at', 'asc');

        if ($request->has('last_id')) {
            $query->where('id', '>', $request->last_id);
        }

        $newReplies = $query->get();

        // Mark chat as read if there are new farmer messages
        if ($newReplies->where('sender_type', 'farmer')->count() > 0) {
            $chat->update(['is_read' => true]);
        }

        $formattedReplies = $newReplies->map(function($reply) {
            return [
                'id' => $reply->id,
                'message' => $reply->message,
                'sender_type' => $reply->sender_type,
                'time' => $reply->created_at->format('H:i')
            ];
        });

        return response()->json([
            'success' => true,
            'replies' => $formattedReplies
        ]);
    }

    /**
     * Get global unread chats and notifications for the navbar.
     */
    public function getNotifications(Request $request)
    {
        $unreadChats = IncomingChat::with('farmer')
            ->where('is_read', false)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $unreadCount = IncomingChat::where('is_read', false)->count();

        $notifications = $unreadChats->map(function($chat) {
            $name = $chat->farmer ? $chat->farmer->name : $chat->phone;
            return [
                'id' => $chat->id,
                'title' => 'Pesan baru dari ' . $name,
                'message' => \Illuminate\Support\Str::limit($chat->last_message, 40),
                'time' => $chat->updated_at->diffForHumans(null, true, true),
                'link' => route('inbox.index', ['chat' => $chat->id])
            ];
        });

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }
}
