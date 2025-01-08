<?php

namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use App\Models\ClientWebsiteMonitoring;
    use App\Models\MonitoringLog; // Import the MonitoringLog model
    use GuzzleHttp\Client as HttpClient;
    use GuzzleHttp\Exception\RequestException; // Import Guzzle exception handler
    use GuzzleHttp\Exception\ConnectException;
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
                    // Perform the website check and capture response time and status code
                    $start = microtime(true);
                    $response = $this->checkWebsite($website->url, $statusCode);
                    $end = microtime(true);
                    $responseTime = round(($end - $start) * 1000);

                    // Log the monitoring result
                    MonitoringLog::create([
                        'website_id' => $website->id,
                        'url' => $website->url,
                        'time' => Carbon::now()->setTimezone('Asia/Makassar'),
                        'response_time' => $responseTime,
                        'status_code' => $statusCode, // Log the actual status code
                    ]);

                    // Update the last check timestamp
                    $website->last_check_at = Carbon::now();
                    $website->save();

                    if (!$response) {
                        // Log the error and send a notification if the website is down
                        $this->sendNotification($website);
                    }
                }
            }
        }

        protected function checkWebsite($url, &$statusCode)
        {
            try {
                $client = new HttpClient(['timeout' => 10]); // Set timeout to handle long requests
                $response = $client->get($url);
                $statusCode = $response->getStatusCode(); // Capture the status code
                return $statusCode === 200; // Website is up if status is 200
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $statusCode = $e->getResponse()->getStatusCode(); // Capture the real status code from the response
                } else {
                    $statusCode = 500; // If there's no response, use a fallback status code
                }
                return false; // Website is down or has some issue
            } catch (ConnectException $e) {
                $statusCode = 408; // Timeout status code if there's a connection issue
                return false; // Website is down due to timeout
            } catch (\Exception $e) {
                $statusCode = 500; // Default to 500 for other unexpected exceptions
                return false; // Website is down
            }
        }

        protected function sendNotification($website)
        {
            // Fetch the client monitoring record
            $clientMonitoring = $website->client; // Ensure this relationship is set up properly

            if (!$clientMonitoring) {
                $this->error("No client monitoring record associated with website: {$website->name}");
                return;
            }

            if (!$clientMonitoring->bot_token) {
                $this->error("No bot token found for client: {$clientMonitoring->name}");
                return;
            }

            if (!$this->shouldNotify($website)) {
                return; // Exit if the notification interval hasn't been met
            }

            // Fetch the last 5 downtimes from the MonitoringLog
            $downtimeLogs = MonitoringLog::where('website_id', $website->id)
                ->where('status_code', '>=', 400) // Include all error status codes >= 400
                ->orderBy('time', 'desc')
                ->take(5)
                ->get();

            // Group downtimes by date
            $groupedDowntimeLogs = $downtimeLogs->groupBy(function ($log) {
                return Carbon::parse($log->time)->format('d-m-Y'); // Group by date
            });

            // Define HTTP status descriptions
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

            // Format the downtime log message
            $downtimeMessage = '';
            foreach ($groupedDowntimeLogs as $date => $logs) {
                $downtimeMessage .= "Tanggal: {$date}\n";
                foreach ($logs as $log) {
                    $time = Carbon::parse($log->time);
                    $description = $statusDescriptions[$log->status_code] ?? 'Unknown Error'; // Default to 'Unknown Error' if status code is not in the list
                    $downtimeMessage .= "  - Code: {$log->status_code} ({$description}) | " . $time->format('H:i:s') . " |  " . $log->response_time . " ms\n";
                }
            }

            if (empty($downtimeMessage)) {
                $downtimeMessage = "No recent downtimes found.";
            }

            // Construct the full message
            $message = "Website {$website->name} (URL: {$website->url}) is down.\n\n5 Downtime terakhir:\n{$downtimeMessage}";

            // Send the notification via Telegram
            $client = new HttpClient();
            $url = "https://api.telegram.org/bot{$clientMonitoring->bot_token}/sendMessage";

            try {
                $client->post($url, [
                    'form_params' => [
                        'chat_id' => $clientMonitoring->chat_id,
                        'text' => $message,
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
