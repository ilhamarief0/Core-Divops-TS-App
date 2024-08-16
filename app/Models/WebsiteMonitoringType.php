<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteMonitoringType extends Model
{
    use HasFactory;

    public function website()
    {
        return $this->belongsTo(ClientWebsiteMonitoring::class, 'website_monitoring_type_id');
    }
}
