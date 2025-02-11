<?php

namespace App\Console\Commands;

use App\Models\ClientMonitoring;
use Illuminate\Console\Command;
use App\Models\ClientWebsiteMonitoring;
use App\Models\MonitoringLog;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MonitorWebsite extends Command
{
    protected $signature = 'app:monitor-website';
    protected $description = 'Monitor websites and send notifications if they are down';

    public function handle()
    {
        $clients = ClientMonitoring::where('is_active', true)
            ->with(['websites' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        if ($clients->isEmpty()) {
            Log::info('No active clients with active websites found.');
            return;
        }

        $httpClient = new HttpClient(['timeout' => 10]);

        foreach ($clients as $client) {
            foreach ($client->websites ?? [] as $website) {
                if ($website->needToCheck()) {
                    $this->checkAndLogWebsite($httpClient, $website);
                }
            }
        }
    }

    protected function checkAndLogWebsite($httpClient, $website)
    {
        $start = microtime(true);
        $response = $this->checkWebsite($httpClient, $website->url, $statusCode);
        $responseTime = round((microtime(true) - $start) * 1000);

        MonitoringLog::create([
            'website_id' => $website->id,
            'url' => $website->url,
            'time' => Carbon::now()->setTimezone('Asia/Makassar'),
            'response_time' => $responseTime,
            'status_code' => $statusCode,
        ]);

        $website->last_check_at = Carbon::now();
        $website->save();

        if (!$response) {
            $this->sendNotification($website);
        }
    }

    protected function checkWebsite($httpClient, $url, &$statusCode)
    {
        try {
            $response = $httpClient->get($url);
            $statusCode = $response->getStatusCode();
            return $statusCode === 200;
        } catch (RequestException $e) {
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 500;
            return false;
        } catch (ConnectException $e) {
            $statusCode = 408;
            return false;
        } catch (\Exception $e) {
            $statusCode = 500;
            return false;
        }
    }

    protected function sendNotification($website)
    {
        $clientMonitoring = $website->client;

        if (!$clientMonitoring || !$clientMonitoring->bot_token) {
            $this->error("No client monitoring record or bot token found for website: {$website->name}");
            return;
        }

        if (!$this->shouldNotify($website)) {
            return;
        }

        $downtimeLogs = MonitoringLog::where('website_id', $website->id)
            ->where('status_code', '>=', 400)
            ->orderBy('time', 'desc')
            ->take(5)
            ->get();

        $downtimeMessage = $this->formatDowntimeMessage($downtimeLogs);

        $message = "Website {$website->name} (URL: {$website->url}) is down.\n\n5 Downtime terakhir:\n{$downtimeMessage}";

        $this->sendTelegramMessage($clientMonitoring->bot_token, $clientMonitoring->chat_id, $message);

        $website->last_notify_user_at = now();
        $website->save();

        $this->info("Notification sent for website: {$website->name}");
    }

    protected function formatDowntimeMessage($downtimeLogs)
    {
        $statusDescriptions = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            408 => 'Request Timeout',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
        ];

        $groupedDowntimeLogs = $downtimeLogs->groupBy(function ($log) {
            return Carbon::parse($log->time)->format('d-m-Y');
        });

        $downtimeMessage = '';
        foreach ($groupedDowntimeLogs as $date => $logs) {
            $downtimeMessage .= "Tanggal: {$date}\n";
            foreach ($logs as $log) {
                $time = Carbon::parse($log->time)->format('H:i:s');
                $description = $statusDescriptions[$log->status_code] ?? 'Unknown Error';
                $downtimeMessage .= "  - Code: {$log->status_code} ({$description}) | {$time} | {$log->response_time} ms\n";
            }
        }

        return $downtimeMessage ?: "No recent downtimes found.";
    }

    protected function sendTelegramMessage($botToken, $chatId, $message)
    {
        $client = new HttpClient();
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        try {
            $client->post($url, [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text' => $message,
                ],
            ]);
        } catch (\Exception $e) {
            $this->error("Failed to send notification: {$e->getMessage()}");
        }
    }

    protected function shouldNotify($website)
    {
        return !$website->last_notify_user_at || $website->last_notify_user_at->diffInMinutes() >= $website->notify_user_interval;
    }
}
