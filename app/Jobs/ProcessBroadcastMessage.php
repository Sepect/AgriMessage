<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\MessageLog;
use App\Services\FonnteGatewayService;
use Illuminate\Support\Facades\Log;

class ProcessBroadcastMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $messageLog;

    /**
     * Create a new job instance.
     */
    public function __construct(MessageLog $messageLog)
    {
        $this->messageLog = $messageLog;
    }

    /**
     * Execute the job.
     */
    public function handle(FonnteGatewayService $fonnteService): void
    {
        try {
            $response = $fonnteService->sendMessage($this->messageLog->phone, $this->messageLog->content);
            
            if ($response && isset($response['status']) && $response['status']) {
                $this->messageLog->update([
                    'status' => 'sent',
                    'fonnte_id' => $response['id'][0] ?? null
                ]);
            } else {
                $this->messageLog->update(['status' => 'failed']);
                Log::error("Fonnte API Error for Log ID {$this->messageLog->id}: " . json_encode($response));
            }
        } catch (\Exception $e) {
            $this->messageLog->update(['status' => 'failed']);
            Log::error("Exception in ProcessBroadcastMessage for Log ID {$this->messageLog->id}: " . $e->getMessage());
        }

        // Check if all messages for this broadcast are done
        if ($this->messageLog->broadcast_id) {
            $remaining = \App\Models\MessageLog::where('broadcast_id', $this->messageLog->broadcast_id)
                ->where('status', 'queued')
                ->count();
                
            if ($remaining === 0) {
                \App\Models\Broadcast::where('id', $this->messageLog->broadcast_id)
                    ->update(['status' => 'completed']);
            }
        }
    }
}
