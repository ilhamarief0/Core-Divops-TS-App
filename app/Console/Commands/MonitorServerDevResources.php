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

            // Ambil penggunaan CPU untuk core 0 dan core 1 dari /proc/stat
            $cpuCore0 = $this->getCpuUsageForCore(0);
            $cpuCore1 = $this->getCpuUsageForCore(1);

            if ($cpuCore0 === null || $cpuCore1 === null) {
                Log::error('Failed to retrieve CPU load for core 0 or core 1.');
                return;
            }

            // Hitung rata-rata penggunaan CPU untuk core 0 dan core 1
            $averageCpuLoad = ($cpuCore0 + $cpuCore1) / 2;
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
                'cpu_usage' => round($averageCpuLoad),
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
        } catch (\Exception $e) {
            Log::error('Error in MonitorServerResources command: ' . $e->getMessage());
        }
    }

    private function getCpuUsageForCore(int $core)
    {
        $statFile = file_get_contents('/proc/stat');
        if ($statFile === false) {
            Log::error("Failed to read /proc/stat");
            return null;
        }

        $lines = explode("\n", $statFile);

        // Cari baris yang sesuai dengan core yang diinginkan, seperti "cpu0" atau "cpu1"
        foreach ($lines as $line) {
            if (strpos($line, "cpu$core") === 0) {
                $parts = array_values(array_filter(explode(" ", $line)));
                $idleTime = $parts[4];  // waktu idle
                $totalTime = array_sum(array_slice($parts, 1));  // waktu total

                return $this->calculateCpuUsage($idleTime, $totalTime);
            }
        }

        Log::error("No data found for core $core in /proc/stat");
        return null;
    }

    private function calculateCpuUsage($idleTime, $totalTime)
    {
        // Simpan nilai idle dan total sebelumnya
        static $lastIdleTime = 0;
        static $lastTotalTime = 0;

        $idleDiff = $idleTime - $lastIdleTime;
        $totalDiff = $totalTime - $lastTotalTime;

        // Simpan nilai idle dan total saat ini untuk perhitungan berikutnya
        $lastIdleTime = $idleTime;
        $lastTotalTime = $totalTime;

        // Hitung penggunaan CPU dalam persen
        if ($totalDiff === 0) {
            return 0;
        }

        $cpuUsage = (1 - ($idleDiff / $totalDiff)) * 100;

        return $cpuUsage;
    }
}
