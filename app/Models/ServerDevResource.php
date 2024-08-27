<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerDevResource extends Model
{
    use HasFactory;

    protected $fillable = ['cpu_usage', 'memory_usage', 'disk_usage'];
}
