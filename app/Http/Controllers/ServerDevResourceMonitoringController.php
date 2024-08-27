<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerDevResourceMonitoringController extends Controller
{
    public function index()
    {
        return view('serverdev.resourcemonitoring.index');
    }
}
