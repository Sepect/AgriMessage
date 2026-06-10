<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageLog;
use App\Models\IncomingChat;
use App\Models\ChatReply;
use App\Models\Farmer;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function fonnte(Request $request)
    {
        Log::info('Fonnte Webhook Received: ', $request->all());

        // Process status update or incoming message
        $sender = $request->input('sender');
        $message = $request->input('message');
        $status = $request->input('status'); // typically sent, delivered, read

        if ($status && $request->has('id')) {
            // Update message log status based on Fonnte ID
            MessageLog::where('fonnte_id', $request->input('id'))
                ->update(['status' => strtolower($status)]);
            return response()->json(['success' => true, 'message' => 'Status updated']);
        }

        if ($message && $sender) {
            // Incoming message
            // Clean phone number
            $phone = preg_replace('/[^0-9]/', '', $sender);
            
            $farmer = Farmer::where('phone', $phone)
                ->orWhere('phone', '+'.$phone)
                ->first();

            $incomingChat = IncomingChat::firstOrCreate(
                ['phone' => $phone],
                ['farmer_id' => $farmer ? $farmer->id : null]
            );

            $incomingChat->update([
                'last_message' => $message,
                'is_read' => false
            ]);

            ChatReply::create([
                'incoming_chat_id' => $incomingChat->id,
                'sender_type' => 'farmer',
                'message' => $message
            ]);

            return response()->json(['success' => true, 'message' => 'Incoming message saved']);
        }

        return response()->json(['success' => true]);
    }
}
