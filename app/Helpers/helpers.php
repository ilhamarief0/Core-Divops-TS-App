<?php

use App\Models\ClientWebsiteMonitoring;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


if (!function_exists('notifyTelegramUser')) {
    function notifyTelegramUser(ClientWebsiteMonitoring $customerSite, Collection $responseTimes, $status = "Slow"): void
    {
        if (is_null($customerSite->vendor)) {
            Log::channel('daily')->info('Missing vendor for customer site', $customerSite->toArray());
            return;
        }

        $vendor = $customerSite->vendor;
        if (is_null($vendor->bot_token) || is_null($vendor->chat_id)) {
            Log::channel('daily')->info('Missing bot_token or chat_id for vendor', $vendor->toArray());
            return;
        }

        $endpoint = 'https://api.telegram.org/bot' . $vendor->bot_token . '/sendMessage';
        $text = "";
        $text .= "Uptime: Website $status";
        $text .= "\n\n" . $customerSite->name . ' (' . $customerSite->url . ')';
        $text .= "\n\nLast 5 response time:";
        $text .= "\n";
        foreach ($responseTimes as $responseTime) {
            $text .= "Code : $responseTime->status_code | " . $responseTime->created_at->format('H:i:s') . ':   ' . $responseTime->response_time . ' ms';
            $text .= "\n";
        }
        $text .= "\nCheck here:";

        if ($customerSite->visibility == "public") {
            $text .= "\n" . route('customer_sites.public-show', [$customerSite->id]);
        } else {
            $text .= "\n" . route('customer_sites.show', [$customerSite->id]);
        }

        Http::post($endpoint, [
            'chat_id' => $vendor->chat_id,
            'text' => $text,
        ]);
    }
}
