<?php

namespace App\Http\Controllers;

use App\Models\ClientMonitoring;
use App\Models\ClientWebsiteMonitoring;
use App\Models\WebsiteMonitoringType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClientWebsiteMonitoringWebController extends Controller
{

    public function dataTable(Request $request)
    {
        if ($request->ajax()) {

            $data = ClientWebsiteMonitoring::query();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('name', function ($row) {
                    return '<a href="' . route('clientwebsitemonitoring.show', Crypt::encryptString($row->id)) . '" class="text-gray-800 text-hover-primary mb-12">' . $row->name . '</a>';
                })

                ->addColumn('url', function ($row) {
                    return '<span class="badge badge-light-secondary">' . $row->url . '</span>';
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == 1) {
                        return '<span class="badge badge-light-success">Active</span>';
                    } else {
                        return '<span class="badge badge-light-secondary">Inactive</span>';
                    }
                })
                ->addColumn('client', function ($row) {
                    return '<span class="badge badge-light-primary">' . $row->client->name . '</span>';
                })
                ->addColumn('type', function ($row) {
                    return '<span class="badge badge-light-primary">' . $row->type->name . '</span>';
                })
                ->addColumn('notify_user_interval', function ($row) {
                    return '<span class="badge badge-light-primary">' . $row->notify_user_interval . ' Menit</span>';
                })

                ->rawColumns(['name', 'url', 'is_active', 'notify_user_interval', 'client', 'type'])
                ->make(true);
        }
    }

    public function index()
    {
        $client = ClientMonitoring::get();
        $websitetype = WebsiteMonitoringType::get();

        return view('monitoringweb.website.list', compact('client', 'websitetype'));
    }

    public function bulkDelete(Request $request)
    {

        $ids = $request->ids;

        DB::beginTransaction();

        try {
            ClientWebsiteMonitoring::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json(['message' => 'Selected records have been deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:60',
            'url' => 'required|max:255',
            'client_monitoring_id' => 'nullable|exists:client_monitorings,id',
            'website_monitoring_type_id' => 'nullable|exists:website_monitoring_types,id',
            'warning_treshold' => 'required',
            'down_treshold' => 'required',
            'notify_user_interval' => 'required',
        ]);

        $customerSite = ClientWebsiteMonitoring::create($validatedData);

        return redirect()->back();
    }

    public function delete($id)
    {
        // Temukan user berdasarkan ID atau gagal dengan notifikasi error
        $website = ClientWebsiteMonitoring::findOrFail($id);
        // Hapus user
        $website->delete();
        // Kembalikan response sukses
        return response()->json(['message' => 'Client deleted successfully'], 200);
    }

    public function getData(ClientWebsiteMonitoring $id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data User',
            'data'    => $id
        ]);
    }

    public function update(Request $request, ClientWebsiteMonitoring $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'url' => 'required',
            'client_monitoring_id' => 'required',
            'website_monitoring_type_id' => 'required',
            'warning_treshold' => 'required',
            'down_treshold' => 'required',
            'notify_user_interval' => 'required',
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;

        $id->update($validatedData);

        return response()->json([
            'message' => 'Client updated successfully',
            'data' => $id
        ], 200);
    }

    private function getStartTimeByTimeRange(string $timeRange): Carbon
    {
        switch ($timeRange) {
            case '6h':
                return Carbon::now()->subHours(6);
            case '24h':
                return Carbon::now()->subHours(24);
            case '7d':
                return Carbon::now()->subDays(7);
            case '14d':
                return Carbon::now()->subDays(14);
            case '30d':
                return Carbon::now()->subDays(30);
            case '3Mo':
                return Carbon::now()->subMonths(3);
            case '6Mo':
                return Carbon::now()->subMonths(6);
            default:
                return Carbon::now()->subHours(1);
        }
    }

    public function show(Request $request, $encryptedCustomerSiteId)
    {
        $customerSiteId = Crypt::decryptString($encryptedCustomerSiteId);

        $customerSite = ClientWebsiteMonitoring::findOrFail($customerSiteId);

        $timeRange = request('time_range', '1h');
        $startTime = $this->getStartTimeByTimeRange($timeRange);

        if ($request->get('start_time')) {
            $timeRange = null;
            $startTime = Carbon::parse($request->get('start_time'));
        }

        $endTime = Carbon::now();
        if ($request->get('end_time')) {
            $endTime = Carbon::parse($request->get('end_time'));
        }

        $logQuery = DB::table('monitoring_logs');
        $logQuery->where('website_id', $customerSite->id);
        $logQuery->whereBetween('created_at', [$startTime, $endTime]);
        $monitoringLogs = $logQuery->get(['response_time', 'created_at']);

        $chartData = [];
        foreach ($monitoringLogs as $monitoringLog) {
            $chartData[] = ['x' => $monitoringLog->created_at, 'y' => $monitoringLog->response_time];
        }

        return view('monitoringweb.website.show', compact('customerSite', 'chartData', 'startTime', 'endTime', 'timeRange'));
    }
}
