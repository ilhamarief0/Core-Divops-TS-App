<?php

namespace App\Http\Controllers;

use App\Models\ClientMonitoring;
use App\Models\ClientWebsiteMonitoring;
use Illuminate\Http\Request;

class OverviewMonitoringController extends Controller
{
    public function index(Request $request)
    {
            $customerSiteQuery = ClientWebsiteMonitoring::query();
            $customerSiteQuery->where('is_active', 1);
            $customerSiteQuery->where('name', 'like', '%' . $request->get('q') . '%');
            $customerSiteQuery->orderBy('client_monitoring_id');
            $customerSiteQuery->orderBy('name');


            // Filter berdasarkan client_monitoring_id
            if ($vendorId = $request->get('client_monitoring_id')) {
                if ($vendorId == 'null') {
                    $customerSiteQuery->whereNull('client_monitoring_id');
                } else {
                    $customerSiteQuery->where('client_monitoring_id', $vendorId);
                }
            }

            // Mengambil data situs pelanggan beserta relasi dengan client
            $customerSites = $customerSiteQuery->with('client')->get();

            // Mengambil daftar vendor yang tersedia
            $availableVendors = ClientMonitoring::orderBy('name')->pluck('name', 'id')->toArray();
            $availableVendors = ['null' => 'n/a'] + $availableVendors;

            // Memproses data untuk ditampilkan di blok per vendor
            $theBlock = array();
            $theCustomerSites = array();
            $chartData = array();  // Data untuk chart

            foreach ($customerSites as $site) {
                // Memasukkan situs ke dalam blok berdasarkan nama vendor
                $theBlock[$site->client->name][] = $site;

                // Mengumpulkan data chart berdasarkan setiap situs pelanggan
                $chartData[$site->id] = [
                    'response_time' => $site->response_time, // Sesuaikan dengan field di database
                    'down_threshold' => $site->down_threshold, // Sesuaikan dengan field di database
                ];
            }

            // Format data untuk dikirim ke view
            foreach ($theBlock as $theVendor => $site) {
                $theCustomerSites[] = [
                    'vendor' => $theVendor,
                    'data' => $site
                ];
            }

            return view('monitoringweb.index', compact('theCustomerSites', 'availableVendors', 'chartData'));
    }
}
