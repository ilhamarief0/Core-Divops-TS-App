<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ForumRecapsApiController extends Controller
{
   private function getJwtToken()
    {
        $username = config('services.forum_api.username');
        $password = config('services.forum_api.password');
        $loginUrl = config('services.forum_api.login_url');

        if (!$username || !$password || !$loginUrl) {
            Log::error('Forum API credentials or login URL not configured in services.php.');
            return null;
        }

        // Try to retrieve from cache first
        if (Cache::has('jwt_token')) {
            return Cache::get('jwt_token');
        }

        try {
            $authResponse = Http::post($loginUrl, [
                'username' => $username,
                'password' => $password,
            ]);

            if ($authResponse->successful() && isset($authResponse->json()['token'])) {
                $token = $authResponse->json()['token'];
                // Cache the token for 55 minutes (just under an hour for safety)
                Cache::put('jwt_token', $token, now()->addMinutes(55));
                return $token;
            }

            Log::warning('Forum API authentication failed.', ['status' => $authResponse->status(), 'response' => $authResponse->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Exception during forum API authentication: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch and filter forum recap data (weekly or monthly).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type 'weekly' or 'monthly'
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecapData(Request $request, string $type)
    {
        $jwtToken = $this->getJwtToken();
        if (!$jwtToken) {
            return response()->json(['error' => 'Unable to authenticate with external forum API'], 401);
        }

        $endpoint = '';
        if ($type === 'weekly') {
            $endpoint = config('services.forum_api.weekly_recap_url');
        } elseif ($type === 'monthly') {
            $endpoint = config('services.forum_api.monthly_recap_url');
        } else {
            return response()->json(['error' => 'Invalid recap type specified. Use "weekly" or "monthly".'], 400);
        }

        if (!$endpoint) {
            Log::error("Forum API endpoint for {$type} recap not configured in services.php.");
            return response()->json(['error' => 'Forum API endpoint not configured.'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $jwtToken,
            ])->get($endpoint);

            if ($response->successful()) {
                $apiResponse = $response->json();

                if (!isset($apiResponse['data']) || !is_array($apiResponse['data'])) {
                    Log::error("Invalid data format from external forum {$type} recap API.", ['response' => $apiResponse]);
                    return response()->json(['error' => 'Invalid data format received from forum API'], 500);
                }

                $nestedData = $apiResponse['data'];
                $flattenedData = [];

                foreach ($nestedData as $periodData) {
                    $tahun = $periodData['tahun'] ?? null;
                    $bulan = $periodData['bulan'] ?? null;
                    $minggu = $periodData['minggu'] ?? null; // Only for weekly

                    if (isset($periodData['tags']) && is_array($periodData['tags'])) {
                        foreach ($periodData['tags'] as $tagData) {
                            if (isset($tagData['divisi'], $tagData['total_postingan'])) {
                                $item = [
                                    'tahun' => $tahun,
                                    'bulan' => $bulan,
                                    'divisi' => $tagData['divisi'],
                                    'total_postingan' => $tagData['total_postingan'],
                                ];
                                if ($type === 'weekly') {
                                    $item['minggu'] = $minggu;
                                }
                                $flattenedData[] = $item;
                            }
                        }
                    }
                }

                // Apply filtering based on request parameters
                $filteredData = collect($flattenedData)->filter(function ($item) use ($request, $type) {
                    $matches = true;
                    if ($request->has('tahun') && $request->tahun) {
                        $matches = $matches && (isset($item['tahun']) && $item['tahun'] == $request->tahun);
                    }
                    if ($request->has('bulan') && $request->bulan) {
                        $matches = $matches && (isset($item['bulan']) && $item['bulan'] == $request->bulan);
                    }
                    if ($type === 'weekly' && $request->has('minggu') && $request->minggu) {
                        $matches = $matches && (isset($item['minggu']) && $item['minggu'] == $request->minggu);
                    }
                    return $matches;
                })->values()->all();

                return response()->json(['data' => $filteredData]);

            } else {
                $statusCode = $response->status();
                $errorMessage = $response->body();
                Log::error("Error fetching data from external forum API ({$type} recap): Status {$statusCode}", ['details' => $errorMessage]);
                return response()->json(['error' => "Error fetching data from external forum API: Status {$statusCode}", 'details' => $errorMessage], $statusCode);
            }
        } catch (\Exception $e) {
            Log::error('Exception during external forum API data fetching: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred during API call.', 'details' => $e->getMessage()], 500);
        }
    }
}
