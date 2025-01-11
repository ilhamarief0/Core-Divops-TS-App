<?php

namespace App\Http\Controllers;

use App\Models\monitoring_server;
use Illuminate\Http\Request;

class MonitoringServerController extends Controller
{
    public function indexstatusserver()
    {
        $servers = monitoring_server::with(['logs' => function ($query) {
            $query->latest()->limit(90); // Ambil 90 log terakhir untuk setiap server
        }])->get();

        return view('monitoringserver.dashboard', compact('servers'));
    }

    public function checkServers()
    {
        $servers = monitoring_server::all();
        foreach ($servers as $server) {
            $connection = @fsockopen($server->ip_address, $server->port, $errno, $errstr, 5);

            if ($connection) {
                $server->update([
                    'status' => 'Operational',
                ]);
                fclose($connection);
            } else {
                $server->update([
                    'status' => 'Down',
                ]);
            }
        }

        return redirect()->route('dashboard.monitoringserver')->with('success', 'Servers updated successfully!');
    }
}
