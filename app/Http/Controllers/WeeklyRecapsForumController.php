<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;

class WeeklyRecapsForumController extends Controller
{
    private function getJwtToken()
    {
        try {
             $authResponse = Http::post('http://localhost:3000/api/login', [
                 'username' => 'adminforumaccess',
                 'password' => 'pass1234',
             ]);

             if ($authResponse->successful() && isset($authResponse->json()['token'])) {
                 return $authResponse->json()['token'];
             }

             return null;
         } catch (\Exception $e) {
             return null;
         }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $jwtToken = $this->getJwtToken();
            if (!$jwtToken) {
                return response()->json(['error' => 'Unable to authenticate with forum API'], 401);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $jwtToken,
            ])->get('http://localhost:3000/api/forum/weeklyrecap');

            if ($response->successful()) {
                $apiResponse = $response->json();

                if (!isset($apiResponse['data']) || !is_array($apiResponse['data'])) {
                     return response()->json(['error' => 'Invalid data format received from forum API'], 500);
                }

                $nestedData = $apiResponse['data'];
                $flattenedData = [];

                foreach ($nestedData as $weekData) {
                    if (isset($weekData['tahun'], $weekData['bulan'], $weekData['minggu'], $weekData['tags']) && is_array($weekData['tags'])) {
                        $tahun = $weekData['tahun'];
                        $bulan = $weekData['bulan'];
                        $minggu = $weekData['minggu'];

                        foreach ($weekData['tags'] as $tagData) {
                            if (isset($tagData['divisi'], $tagData['total_postingan'])) {
                                $flattenedData[] = [
                                    'tahun' => $tahun,
                                    'bulan' => $bulan,
                                    'minggu' => $minggu,
                                    'divisi' => $tagData['divisi'],
                                    'total_postingan' => $tagData['total_postingan'],
                                ];
                            }
                        }
                    }
                }

                $filteredData = $flattenedData;

                if ($request->has('tahun') && $request->tahun) {
                    $filteredData = array_filter($filteredData, function ($item) use ($request) {
                        return isset($item['tahun']) && $item['tahun'] == $request->tahun;
                    });
                }

                 if ($request->has('bulan') && $request->bulan) {
                    $filteredData = array_filter($filteredData, function ($item) use ($request) {
                         return isset($item['bulan']) && $item['bulan'] == $request->bulan;
                    });
                }

                if ($request->has('minggu') && $request->minggu) {
                    $filteredData = array_filter($filteredData, function ($item) use ($request) {
                        return isset($item['minggu']) && $item['minggu'] == $request->minggu;
                    });
                }

                $filteredData = array_values($filteredData);

                return DataTables::of($filteredData)
                    ->addIndexColumn()
                     ->addColumn('tahun', function($row) {
                         return $row['tahun'] ?? '';
                     })
                     ->addColumn('bulan', function($row) {
                         return $row['bulan'] ?? '';
                     })
                     ->addColumn('minggu', function($row) {
                         return $row['minggu'] ?? '';
                     })
                     ->addColumn('divisi', function($row) {
                         return $row['divisi'] ?? '';
                     })
                    ->addColumn('total_postingan', function ($row) {
                         $totalPostingan = $row['total_postingan'] ?? 0;
                        return '
                            <div class="flex text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-primary">' . $totalPostingan . '</span>
                            </div>';
                    })
                    ->rawColumns(['total_postingan'])
                    ->make(true);
            }

            $statusCode = $response->status();
            $errorMessage = $response->body();

            return response()->json(['error' => "Error fetching data from forum API: Status {$statusCode}", 'details' => $errorMessage], $statusCode);

        }

        return view('forumrecaps.weekly.index');
    }
}
