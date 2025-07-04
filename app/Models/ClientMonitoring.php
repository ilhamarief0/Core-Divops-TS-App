<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMonitoring extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active', 'description', 'bot_token', 'chat_id', 'creator_id'];


    public function websites()
    {
        return $this->hasMany(ClientWebsiteMonitoring::class, 'client_monitoring_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
