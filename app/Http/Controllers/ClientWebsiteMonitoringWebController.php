<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientWebsiteMonitoringWebController extends Controller
{
    public function index()
    {
        return view('monitoringweb.website.list');
    }
}
