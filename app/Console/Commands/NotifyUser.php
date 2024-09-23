<?php

namespace App\Console\Commands;

use App\Models\ClientWebsiteMonitoring;
use App\Models\MonitoringLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyUser extends Command
{
    protected $signature = 'notify-user';

    protected $description = 'Notify user for website down';

    public function handle(): void
    {
        Log::info('NotifyUser command started.');

        $customerSites = ClientWebsiteMonitoring::where('is_active', 1)->get();
        Log::info('Fetched active customer sites: ' . $customerSites->count());

        foreach ($customerSites as $customerSite) {
            if (!$customerSite->canNotifyUser()) {
                Log::info('Skipping site ID ' . $customerSite->id . ' - Notification conditions not met.');
                continue;
            }

            $responseTimes = MonitoringLog::query()
                ->where('website_id', $customerSite->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['response_time', 'status_code', 'created_at']);
            Log::info('Fetched response times for site ID ' . $customerSite->id . ': ' . $responseTimes->count());

            $responseTimeAverage = $responseTimes->avg('response_time');
            Log::info('Average response time for site ID ' . $customerSite->id . ': ' . $responseTimeAverage);

            $latest_log = $responseTimes->first();
            $webStatus = ($latest_log->status_code != 200) ? "Down" : "Slow";

            Log::info('Site ID ' . $customerSite->id . ' status: ' . $webStatus);

            // Assuming `notifyTelegramUser` is a global helper or service method
            notifyTelegramUser($customerSite, $responseTimes, $webStatus);

            $customerSite->last_notify_user_at = Carbon::now();
            $customerSite->save();

            Log::info('Notification sent and last_notify_user_at updated for site ID ' . $customerSite->id);
            // if ($responseTimeAverage >= ($customerSite->down_threshold * 0.9)) {
            // }
        }

        Log::info('NotifyUser command completed.');
        $this->info('Done!');
    }
}
