<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringLog extends Model
{
    use HasFactory;

    protected $fillable = ['website_id', 'url', 'status_code', 'response_time'];

    public function customerSite()
    {
        return $this->belongsTo(ClientWebsiteMonitoring::class)->withDefault(['name' => 'n/a']);
    }
}
