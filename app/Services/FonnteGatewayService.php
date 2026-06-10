<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteGatewayService
{
    protected $token;
    protected $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
    }

    public function sendMessage($phone, $message)
    {
        if (empty($this->token)) {
            Log::warning("Fonnte API Token is empty. Message to {$phone} was not sent.");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
                'delay' => '2',
                'countryCode' => '62',
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Failed to send message via Fonnte: " . $e->getMessage());
            return false;
        }
    }
}
