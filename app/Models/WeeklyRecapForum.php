<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyRecapForum extends Model
{
    use HasFactory;

    protected $fillable = ['divisi', 'total_postingan', 'minggu', 'bulan', 'tahun'];
}
