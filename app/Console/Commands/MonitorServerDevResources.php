<?php

namespace App\Console\Commands;

use App\Models\ServerDevResource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MonitorServerDevResources extends Command
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

            // Ambil nilai CPU usage untuk core 0 dan 1
            $cpuLoadCore0 = shell_exec("mpstat -P 0 1 1 | grep 'Average' | awk '{print $3}'");
            $cpuLoadCore1 = shell_exec("mpstat -P 1 1 1 | grep 'Average' | awk '{print $3}'");

            if ($cpuLoadCore0 === null || $cpuLoadCore1 === null) {
                Log::error('Failed to retrieve CPU load for core 0 or core 1.');
                return;
            }

            $cpuLoadCore0 = 100 - (float)trim($cpuLoadCore0); // Menghitung penggunaan CPU (idle = 100% - usage%)
            $cpuLoadCore1 = 100 - (float)trim($cpuLoadCore1); // Menghitung penggunaan CPU (idle = 100% - usage%)

            $averageCpuLoad = ($cpuLoadCore0 + $cpuLoadCore1) / 2; // Menghitung rata-rata penggunaan CPU
            Log::info('Average CPU Load (Core 0 and 1): ' . $averageCpuLoad);

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

            // Simpan ke database sebagai integer
            ServerDevResource::create([
                'cpu_usage' => round($averageCpuLoad), // Menggunakan fungsi round() untuk membulatkan nilai
                'memory_usage' => round($memoryUsage),
                'disk_usage' => round($diskUsage),
            ]);

            Log::info('Data successfully saved to database.');

            // Hapus data lama setelah setiap 10 data
            $totalEntries = ServerDevResource::count();
            if ($totalEntries > 10) {
                $entriesToDelete = $totalEntries - 10;
                ServerDevResource::orderBy('id', 'asc')->limit($entriesToDelete)->delete();
                Log::info("Deleted $entriesToDelete old entries from database.");
            }

            // Hitung rata-rata penggunaan dan update data terakhir
            $averageCpu = round(ServerDevResource::average('cpu_usage'));
            $averageMemory = round(ServerDevResource::average('memory_usage'));
            $averageDisk = round(ServerDevResource::average('disk_usage'));

            // Simpan data rata-rata ke database
            ServerDevResource::where('id', ServerDevResource::max('id'))->update([
                'cpu_usage' => $averageCpu,
                'memory_usage' => $averageMemory,
                'disk_usage' => $averageDisk,
            ]);

            Log::info('Average data successfully updated in database.');
        } catch (\Exception $e) {
            Log::error('Error in MonitorServerResources command: ' . $e->getMessage());
        }
    }
}
