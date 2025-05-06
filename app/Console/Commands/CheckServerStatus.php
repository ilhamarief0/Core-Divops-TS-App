<?php

namespace App\Console\Commands;

use App\Models\monitoring_server;
use App\Models\MonitoringServerLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckServerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-server-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $servers = monitoring_server::all();

    foreach ($servers as $server) {
        try {
            // Ambil port dari database (misalnya, bisa ditambahkan kolom 'port' pada tabel monitoring_server)
            $port = $server->port;

            // Periksa apakah port yang ditentukan terbuka di server
            $connection = @fsockopen($server->ip_address, $port, $errno, $errstr, 5); // Timeout 5 detik

            if (is_resource($connection)) {
                fclose($connection); // Tutup koneksi setelah diperiksa
                $server->status = 'Operational';
                $server->uptime_percentage = min($server->uptime_percentage + 1, 100);
            } else {
                $server->status = 'Down';
            }
        } catch (\Exception $e) {
            $server->status = 'Down';
        }

        $server->save();


        MonitoringServerLog::create([
            'server_id' => $server->id,
            'status' => $server->status,
            'checked_at' => now(),
        ]);
    }

    $this->info('Server statuses and logs have been updated successfully!');
    return Command::SUCCESS;
}

}
