<?php

namespace App\Console\Commands;

use App\Models\ServerDevResource;
use Illuminate\Console\Command;
use App\Models\ServerResource;
use Illuminate\Support\Facades\DB;

class MonitorServerResources extends Command
{
    protected $signature = 'monitor:resources';

    protected $description = 'Monitor CPU, Memory, and Disk Usage of the Server';

    public function handle()
    {
        while (true) {
            // Ambil nilai CPU usage
            $cpuLoad = sys_getloadavg()[0];

            // Ambil nilai Memory usage
            $free = shell_exec('free');
            $free = (string)trim($free);
            $free_arr = explode("\n", $free);
            $mem = explode(" ", $free_arr[1]);
            $mem = array_filter($mem);
            $mem = array_merge($mem);
            $memoryUsage = ($mem[2] / $mem[1]) * 100;

            // Ambil nilai Disk usage
            $diskUsage = (disk_total_space("/") - disk_free_space("/")) / disk_total_space("/") * 100;

            // Simpan ke database
            ServerDevResource::create([
                'cpu_usage' => $cpuLoad,
                'memory_usage' => $memoryUsage,
                'disk_usage' => $diskUsage,
            ]);

            // Tunggu selama 5 detik sebelum mengambil data berikutnya
            sleep(5);
        }
    }
}
