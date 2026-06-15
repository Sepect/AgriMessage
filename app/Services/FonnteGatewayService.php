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
        $dbToken = \App\Models\WaSetting::where('key', 'fonnte_token')->value('value');
        $this->token = $dbToken ?: config('services.fonnte.token', '');
    }

    public function sendMessage($phone, $message)
    {
        if (empty($this->token)) {
            Log::warning("Fonnte API Token is empty. Message to {$phone} was not sent.");
            return false;
        }

        // Sanitize phone number (remove non-digits like +, -, spaces)
        $phone = preg_replace('/[^0-9]/', '', $phone);

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
    public function getDeviceStatus()
    {
        if (empty($this->token)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/device');

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Failed to check Fonnte device status: " . $e->getMessage());
            return false;
        }
    }

    public function getQrCode()
    {
        if (empty($this->token)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
                'Content-Type' => 'application/json',
            ])->post('https://api.fonnte.com/qr', [
                        'type' => 'base64'
                    ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Failed to get Fonnte QR Code: " . $e->getMessage());
            return false;
        }
    }

    public function disconnectDevice()
    {
        if (empty($this->token)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/disconnect');

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Failed to disconnect Fonnte device: " . $e->getMessage());
            return false;
        }
    }
}
