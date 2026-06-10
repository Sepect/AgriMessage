<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FonnteGatewayService;

class TestFonnte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fonnte:test {phone?} {message?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Fonnte API connection';

    /**
     * Execute the console command.
     */
    public function handle(FonnteGatewayService $fonnte)
    {
        $this->info("Checking Fonnte configuration...");
        $token = config('services.fonnte.token');
        
        if (empty($token)) {
            $this->error("FONNTE_TOKEN is not set in .env!");
            return Command::FAILURE;
        }
        
        $this->info("Token found: " . substr($token, 0, 4) . "...");
        
        $phone = $this->argument('phone');
        $message = $this->argument('message') ?? "Test message from AgriMessage.";
        
        if (!$phone) {
            $this->warn("No phone number provided. Skipping send test.");
            return Command::SUCCESS;
        }
        
        $this->info("Attempting to send message to {$phone}...");
        
        $response = $fonnte->sendMessage($phone, $message);
        
        if ($response) {
            if (isset($response['status']) && $response['status']) {
                $this->info("Success! Message sent successfully.");
                $this->line(json_encode($response, JSON_PRETTY_PRINT));
            } else {
                $this->error("Failed! Fonnte returned an error:");
                $this->line(json_encode($response, JSON_PRETTY_PRINT));
            }
        } else {
            $this->error("Failed! No response from service or an exception occurred. Check logs.");
        }
        
        return Command::SUCCESS;
    }
}
