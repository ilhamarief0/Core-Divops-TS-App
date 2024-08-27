<?php

namespace App\Jobs;

use App\Models\ClientWebsiteMonitoring;
use App\Models\MonitoringLog;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $websiteClient;

    /**
     * Create a new job instance.
     */
    public function __construct(ClientWebsiteMonitoring $websiteClient)
    {
        $this->websiteClient = $websiteClient;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting RunCheck job for URL: ' . $this->websiteClient->url);

        $forceNotify = false;
        $notifyStatus = "Down";

        $websiteClient = $this->websiteClient;
        $start = microtime(true);

        // User-Agent strings array
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0'
        ];

        $client = new Client([
            'timeout' => config('queue.default') == 'sync' ? $websiteClient->down_threshold / 1000 : 30,
            'connect_timeout' => 20,
            'cookies' => true,
            'headers' => [
                'User-Agent' => $userAgents[array_rand($userAgents)],
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ],
        ]);

        try {
            $response = $client->get($websiteClient->url);
            $statusCode = $response->getStatusCode();

            if ($statusCode != 200) {
                $forceNotify = true;

                // Jika status code adalah 500, set notifyStatus ke "Server Error"
                if ($statusCode == 500) {
                    $notifyStatus = "Server Error";
                }
            }

            Log::info('Checked URL: ' . $websiteClient->url . ' with status code: ' . $statusCode);
        } catch (RequestException $e) {
            Log::error('RequestException for URL: ' . $websiteClient->url . ' - ' . $e->getMessage());
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 500;
            $forceNotify = true;
            $notifyStatus = $statusCode == 500 ? "Server Error" : "Down";
        } catch (\Exception $e) {
            Log::error('Exception for URL: ' . $websiteClient->url . ' - ' . $e->getMessage());
            $statusCode = 500;
            $forceNotify = true;
            $notifyStatus = "Server Error";
        }

        $end = microtime(true);
        $responseTime = round(($end - $start) * 1000); // Calculate response time in milliseconds

        // WHEN RESPONSE TIME ABOVE "DOWN" THRESHOLD, EVEN IF HTTP STATUS CODE IS 200, NOTIFY USER
        if ($statusCode == 200 && $responseTime >= $websiteClient->down_threshold) {
            $forceNotify = true;
            $notifyStatus = "Hit Down Threshold";
        }

        // Log the monitoring result to the database
        MonitoringLog::create([
            'website_id' => $websiteClient->id,
            'url' => $websiteClient->url,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
        ]);

        Log::info('Logged monitoring result for URL: ' . $websiteClient->url . ' with response time: ' . $responseTime . 'ms and status code: ' . $statusCode);

        $websiteClient->last_check_at = Carbon::now();
        $websiteClient->save();

        // NOTIFY USER IF NEEDED
        if (!empty(config('services.telegram_notifier.token'))) {
            if ($forceNotify && $websiteClient->canNotifyUser()) {
                $responseTimes = MonitoringLog::query()
                    ->where('customer_site_id', $websiteClient->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get(['response_time', 'status_code', 'created_at']);

                notifyTelegramUser($websiteClient, $responseTimes, $notifyStatus);
                $websiteClient->last_notify_user_at = Carbon::now();
                $websiteClient->save();

                Log::info('User notified for URL: ' . $websiteClient->url . ' with status: ' . $notifyStatus);
            }
        }

        Log::info('Completed RunCheck job for URL: ' . $this->websiteClient->url);
    }
}
