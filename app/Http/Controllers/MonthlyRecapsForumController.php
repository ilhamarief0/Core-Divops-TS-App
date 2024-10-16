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
        if (Cache::has('jwt_token')) {
            return Cache::get('jwt_token');
        }

        // Example: Make a POST request to login and get the JWT token
        $response = Http::post('https://monthly.apiforum.divops.devtechnos.com/api/login', [
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
            ])->get('https://monthly.apiforum.divops.devtechnos.com/api/recap'); // Replace with your Python API URL

            // Check if the API call was successful
            if ($response->successful()) {
                $data = $response->json(); // Decode JSON response into array

                if ($request->has('bulan') && $request->bulan) {
                    $data = array_filter($data, function ($item) use ($request) {
                        return $item['bulan'] == $request->bulan;
                    });
                }
                if ($request->has('tahun') && $request->tahun) {
                    $data = array_filter($data, function ($item) use ($request) {
                        return $item['tahun'] == $request->tahun;
                    });
                }

                // Return data to DataTable
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('total_postingan', function ($row) {
                        return '
                            <div class="flex text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-primary">' . $row['total_postingan'] . '</span>
                            </div>';
                    })
                    ->rawColumns(['total_postingan'])
                    ->make(true);
            }

            // If the API call fails, return an error response
            return response()->json(['error' => 'Error fetching data from Python API'], 500);
        }

        return view('forumrecaps.monthly.index');
    }
}
