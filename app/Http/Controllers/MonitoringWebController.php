<?php

namespace App\Http\Controllers;

use App\Models\ClientMonitoring;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MonitoringWebController extends Controller
{
    public function index()
    {
        return view('monitoringweb.index');
    }


}
