<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClientWebsiteMonitoring;
use GuzzleHttp\Client as HttpClient;
use Carbon\Carbon;

class MonitorWebsite extends Command
{
    protected $signature = 'app:monitor-website';
    protected $description = 'Monitor websites and send notifications if they are down';

    public function handle()
    {
        // Fetch all websites to monitor
        $websites = ClientWebsiteMonitoring::where('is_active', true)->get();

        foreach ($websites as $website) {
            // Check if the website needs to be checked
            if ($website->needToCheck()) {
                // Perform the website check (you need to implement this logic)
                $response = $this->checkWebsite($website->url);

                if (!$response) {
                    // Log the error and send a notification if the website is down
                    $this->sendNotification($website);
                }

                // Update the last check timestamp
                $website->last_check_at = now();
                $website->save();
            }
        }
    }

    protected function checkWebsite($url)
    {
        try {
            $client = new HttpClient();
            $response = $client->get($url);
            return $response->getStatusCode() === 200; // Website is up if status is 200
        } catch (\Exception $e) {
            return false; // Website is down
        }
    }

    protected function sendNotification($website)
    {
        // Fetch the client monitoring record
        $clientMonitoring = $website->client; // Ensure this relationship is set up properly

        // Check if client monitoring record exists
        if (!$clientMonitoring) {
            $this->error("No client monitoring record associated with website: {$website->name}");
            return;
        }

        // Check if bot token exists
        if (!$clientMonitoring->bot_token) {
            $this->error("No bot token found for client: {$clientMonitoring->name}");
            return;
        }

        // Ensure the notification interval is met
        if (!$this->shouldNotify($website)) {
            return; // Exit if the notification interval hasn't been met
        }

        $client = new HttpClient();
        $url = "https://api.telegram.org/bot{$clientMonitoring->bot_token}/sendMessage";

        try {
            $client->post($url, [
                'form_params' => [
                    'chat_id' => $clientMonitoring->chat_id,
                    'text' => "Website {$website->name} (URL: {$website->url}) is down.",
                ],
            ]);

            // Update last notified time
            $website->last_notify_user_at = now();
            $website->save();

            $this->info("Notification sent for website: {$website->name}");
        } catch (\Exception $e) {
            $this->error("Failed to send notification: {$e->getMessage()}");
        }
    }

    protected function shouldNotify($website)
    {
        // Check if the last notification was sent recently
        if (!$website->last_notify_user_at || $website->last_notify_user_at->diffInMinutes() >= $website->notify_user_interval) {
            return true;
        }
        return false;
    }
}
