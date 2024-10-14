<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class MonthlyRecapsForumController extends Controller
{
    private function getJwtToken()
    {
        // Check if the token is cached to avoid repeated login requests
        if (Cache::has('jwt_token')) {
            return Cache::get('jwt_token');
        }

        // Example: Make a POST request to login and get the JWT token
        $response = Http::post('https://apiforum.divops.devtechnos.com/api/login', [
            'username' => 'testuser',  // Replace with your actual username
            'password' => 'testpassword',  // Replace with your actual password
        ]);

        // If login is successful, store the token in cache
        if ($response->successful()) {
            $token = $response->json()['token'];

            // Cache the token for 55 minutes (since JWT tokens usually last for an hour)
            Cache::put('jwt_token', $token, now()->addMinutes(55));

            return $token;
        }

        // If login failed, return null
        return null;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get the JWT token
            $jwtToken = $this->getJwtToken();

            if (!$jwtToken) {
                return response()->json(['error' => 'Unable to authenticate'], 401);
            }

            // Fetch data from the Python API with the JWT token in the Authorization header
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $jwtToken,
            ])->get('https://apiforum.divops.devtechnos.com/api/recap'); // Replace with your Python API URL

            // Check if the API call was successful
            if ($response->successful()) {
                $data = $response->json(); // Decode JSON response into array

                // Initialize an array to hold the filtered recap
                $monthlyRecap = [];

                // Retrieve the month and year from the request
                $selectedMonth = $request->input('bulan');
                $selectedYear = $request->input('tahun');

                // Filter data by selected month and year
                foreach ($data as $item) {
                    if ($item['bulan'] == $selectedMonth && $item['tahun'] == $selectedYear) {
                        $divisi = $item['divisi'];
                        $totalPostingan = $item['total_postingan'];

                        // If the division doesn't exist in the summary, initialize it
                        if (!isset($monthlyRecap[$divisi])) {
                            $monthlyRecap[$divisi] = [
                                'divisi' => $divisi,
                                'total_postingan' => 0, // Total posts for the division in the selected month
                            ];
                        }

                        // Sum up total posts for the division in the month
                        $monthlyRecap[$divisi]['total_postingan'] += $totalPostingan;
                    }
                }

                // Format the data to be displayed as a DataTable
                $formattedData = [];
                foreach ($monthlyRecap as $divRecap) {
                    $formattedData[] = [
                        'divisi' => $divRecap['divisi'],
                        'total_postingan' => $divRecap['total_postingan'],
                    ];
                }

                // Return formatted data to DataTable
                return DataTables::of($formattedData)
                    ->addIndexColumn()
                    ->addColumn('divisi', function ($row) {
                        return '<span>' . $row['divisi'] . '</span>';
                    })
                    ->addColumn('total_postingan', function ($row) {
                        return '
                            <div class="flex text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-primary">' . $row['total_postingan'] . '</span>
                            </div>';
                    })
                    ->rawColumns(['total_postingan', 'divisi'])
                    ->make(true);
            }

            // If the API call fails, return an error response
            return response()->json(['error' => 'Error fetching data from Python API'], 500);
        }

        return view('forumrecaps.monthly.index');
    }
}
