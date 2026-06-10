<?php

namespace App\Services;

use App\Models\Broadcast;
use App\Models\MessageLog;
use App\Models\BroadcastRecipient;
use App\Repositories\FarmerRepository;
use App\Jobs\ProcessBroadcastMessage;
use Illuminate\Support\Facades\DB;

class BroadcastService
{
    protected $farmerRepository;

    public function __construct(FarmerRepository $farmerRepository)
    {
        $this->farmerRepository = $farmerRepository;
    }

    public function processBroadcast(Broadcast $broadcast)
    {
        $broadcast->update(['status' => 'processing']);

        $farmers = collect();
        
        $segment = $broadcast->target_segment;
        if (!empty($segment)) {
            if (isset($segment['type'])) {
                if ($segment['type'] === 'all') {
                    $farmers = $this->farmerRepository->getAllActive();
                } elseif ($segment['type'] === 'region') {
                    $farmers = $this->farmerRepository->getByRegion($segment['id']);
                } elseif ($segment['type'] === 'group') {
                    $farmers = $this->farmerRepository->getByGroup($segment['id']);
                }
            }
        }

        if ($farmers->isEmpty()) {
            $broadcast->update(['status' => 'completed']);
            return false;
        }

        DB::transaction(function () use ($broadcast, $farmers) {
            foreach ($farmers as $farmer) {
                BroadcastRecipient::create([
                    'broadcast_id' => $broadcast->id,
                    'farmer_id' => $farmer->id,
                    'status' => 'pending'
                ]);

                $log = MessageLog::create([
                    'broadcast_id' => $broadcast->id,
                    'farmer_id' => $farmer->id,
                    'phone' => $farmer->phone,
                    'content' => $this->parseTemplate($broadcast->content, $farmer),
                    'status' => 'queued'
                ]);

                ProcessBroadcastMessage::dispatch($log)->delay($broadcast->scheduled_at ?? now());
            }
        });

        // Status remains 'processing' until all jobs complete.
        // The last ProcessBroadcastMessage job or a batch callback should update this.
        return true;
    }

    protected function parseTemplate($content, $farmer)
    {
        return str_replace(['[Nama]'], [$farmer->name], $content);
    }
}
