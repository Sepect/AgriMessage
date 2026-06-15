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

            $variants = [$phone, '+' . $phone];
            if (str_starts_with($phone, '62')) {
                $variants[] = '0' . substr($phone, 2);
            } elseif (str_starts_with($phone, '0')) {
                $variants[] = '62' . substr($phone, 1);
            }

            $farmer = Farmer::whereIn('phone', $variants)->first();

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

            // Auto-reply logic
            $autoReplyEnabled = \App\Models\WaSetting::where('key', 'auto_reply_enabled')->value('value');
            if ($autoReplyEnabled == '1') {
                $now = now();
                // Check if outside business hours: Mon-Fri, 08:00 - 16:00
                $isWeekend = $now->isWeekend();
                $isOutsideHours = $now->hour < 8 || $now->hour >= 16;

                if ($isWeekend || $isOutsideHours) {
                    $autoReplyMessage = \App\Models\WaSetting::where('key', 'auto_reply_message')->value('value');
                    if ($autoReplyMessage) {
                        $fonnteService = new \App\Services\FonnteGatewayService();
                        $fonnteService->sendMessage($phone, $autoReplyMessage);

                        ChatReply::create([
                            'incoming_chat_id' => $incomingChat->id,
                            'sender_type' => 'admin', // or 'system'
                            'message' => $autoReplyMessage
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Incoming message saved']);
        }

        return response()->json(['success' => true]);
    }
}
