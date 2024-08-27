<?php

namespace App\Console\Commands;

use App\Models\ServerDevResource;
use App\Models\WeeklyRecapForum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateWeeklyForumStats extends Command
{
    protected $signature = 'update:monitoringserver';
    protected $description = 'Update Server Dev Resources';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            Log::info('Monitoring started.');

            // Ambil nilai CPU usage
            $cpuLoad = sys_getloadavg()[0];
            Log::info('CPU Load: ' . $cpuLoad);

            // Ambil nilai Memory usage
            $free = shell_exec('free');
            if (!$free) {
                Log::error('Failed to retrieve memory usage.');
                return;
            }

            $free = (string)trim($free);
            $free_arr = explode("\n", $free);
            $mem = explode(" ", $free_arr[1]);
            $mem = array_filter($mem);
            $mem = array_merge($mem);
            $memoryUsage = ($mem[2] / $mem[1]) * 100;
            Log::info('Memory Usage: ' . $memoryUsage);

            // Ambil nilai Disk usage
            $diskTotal = disk_total_space("/");
            $diskFree = disk_free_space("/");
            if ($diskTotal === false || $diskFree === false) {
                Log::error('Failed to retrieve disk usage.');
                return;
            }

            $diskUsage = ($diskTotal - $diskFree) / $diskTotal * 100;
            Log::info('Disk Usage: ' . $diskUsage);

            // Simpan ke database
            ServerDevResource::create([
                'cpu_usage' => $cpuLoad,
                'memory_usage' => $memoryUsage,
                'disk_usage' => $diskUsage,
            ]);

            Log::info('Data successfully saved to database.');
        } catch (\Exception $e) {
            Log::error('Error in MonitorServerResources command: ' . $e->getMessage());
        }
    }
}
