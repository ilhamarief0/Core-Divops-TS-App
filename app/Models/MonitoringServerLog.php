<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringServerLog extends Model
{
    use HasFactory;
    protected $fillable = ['server_id', 'status', 'checked_at'];
}
