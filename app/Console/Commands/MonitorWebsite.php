<?php

namespace App\Console\Commands;

use App\Jobs\RunCheck;
use App\Models\ClientWebsiteMonitoring;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MonitorWebsite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monitor-website';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor client websites and dispatch jobs to check their status.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Starting MonitorWebsite command.');

        $websiteClients = ClientWebsiteMonitoring::where('is_active', 1)->get(); // Add your desired URLs here

        foreach ($websiteClients as $customerSite) {
            Log::info('Checking URL: ' . $customerSite->url);

            if (!$customerSite->needToCheck()) {
                Log::info('Skipping URL: ' . $customerSite->url . ' (needToCheck returned false)');
                continue;
            }

            dispatch(new RunCheck($customerSite));
            Log::info('Dispatched RunCheck job for URL: ' . $customerSite->url);
        }

        $this->info('URLs monitored successfully.');
        Log::info('Completed MonitorWebsite command.');
    }
}
