<?php

use App\Models\ClientWebsiteMonitoring;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


if (!function_exists('notifyTelegramUser')) {
    function notifyTelegramUser(ClientWebsiteMonitoring $websiteClient, Collection $responseTimes, $status = "Slow"): void
    {
        if (is_null($websiteClient->client_monitoring)) {
            Log::channel('daily')->info('Missing vendor for customer site', $websiteClient->toArray());
            return;
        }

        $client_monitoring = $websiteClient->client_monitoring;
        if (is_null($client_monitoring->bot_token) || is_null($client_monitoring->chat_id)) {
            Log::channel('daily')->info('Missing bot_token or chat_id for client_monitoring', $client_monitoring->toArray());
            return;
        }

        $endpoint = 'https://api.telegram.org/bot' . $client_monitoring->bot_token . '/sendMessage';
        $text = "";
        $text .= "Uptime: Website $status";
        $text .= "\n\n" . $websiteClient->name . ' (' . $websiteClient->url . ')';
        $text .= "\n\nLast 5 response time:";
        $text .= "\n";
        foreach ($responseTimes as $responseTime) {
            $text .= "Code : $responseTime->status_code | " . $responseTime->created_at->format('H:i:s') . ':   ' . $responseTime->response_time . ' ms';
            $text .= "\n";
        }
        $text .= "\nCheck here:";

        if ($websiteClient->visibility == "public") {
            $text .= "\n" . route('customer_sites.public-show', [$websiteClient->id]);
        } else {
            $text .= "\n" . route('customer_sites.show', [$websiteClient->id]);
        }

        Http::post($endpoint, [
            'chat_id' => $client_monitoring->website_id,
            'text' => $text,
        ]);
    }
}
