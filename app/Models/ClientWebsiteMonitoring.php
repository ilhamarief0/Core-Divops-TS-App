<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientWebsiteMonitoring extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'is_active', 'client_monitoring_id', 'website_monitoring_type_id', 'warning_threshold', 'down_threshold', 'notify_user_interval'];

    protected $casts = [
        'last_check_at' => 'datetime',
        'last_notify_user_at' => 'datetime',
        'visibility' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMonitoring::class, 'client_monitoring_id');
    }
    public function type()
    {
        return $this->belongsTo(WebsiteMonitoringType::class, 'website_monitoring_type_id');
    }

    public function needToCheck(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_check_at) {
            return true;
        }

        if ($this->last_check_at->diffInMinutes() < ($this->check_interval - 1)) {
            return false;
        }

        return true;
    }

    public function canNotifyUser(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_notify_user_at) {
            return true;
        }

        if ($this->last_notify_user_at->diffInMinutes() < ($this->notify_user_interval - 1)) {
            return false;
        }

        return true;
    }
}
