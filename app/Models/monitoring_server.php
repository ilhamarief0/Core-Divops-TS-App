<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class monitoring_server extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'ip_address', 'port', 'status', 'uptime_percentage'];


    public function logs()
    {
        return $this->hasMany(MonitoringServerLog::class, 'server_id');
    }
}
