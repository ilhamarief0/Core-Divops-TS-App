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

        // Ganti URL login dan kredensial sesuai dengan API otentikasi Anda yang sebenarnya
        $response = Http::post('http://localhost:3000/api/login', [
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

            // Fetch data from the API monthly recap endpoint
            // PASTIKAN URL INI BENAR SESUAI DENGAN API RECAP BULANAN ANDA
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $jwtToken,
            ])->get('http://localhost:3000/api/forum/monthlyrecap'); // Menggunakan endpoint yang benar

            // Check if the API call was successful
            if ($response->successful()) {
                $apiResponse = $response->json(); // Decode JSON response into array

                // Validasi struktur dasar respons API: pastikan ada kunci 'data' dan berupa array
                if (!isset($apiResponse['data']) || !is_array($apiResponse['data'])) {
                     // Jika struktur data tidak sesuai, kirim error
                     \Log::error('Invalid data format received from monthly recap API.', ['response' => $apiResponse]); // Tambahkan logging
                     return response()->json(['error' => 'Invalid data format received from forum API'], 500);
                }

                $nestedData = $apiResponse['data']; // Ambil array data bulanan yang bersarang
                $flattenedData = []; // Array untuk menampung data yang sudah diratakan untuk DataTables

                // Loop melalui setiap entri bulan dalam data bersarang
                foreach ($nestedData as $monthData) {
                    // Validasi struktur entri bulan: pastikan ada 'tahun', 'bulan', dan 'tags' (array)
                    if (isset($monthData['tahun'], $monthData['bulan'], $monthData['tags']) && is_array($monthData['tags'])) {
                        $tahun = $monthData['tahun'];
                        $bulan = $monthData['bulan'];

                        // Loop melalui setiap entri tag/divisi dalam array 'tags' bulan ini
                        foreach ($monthData['tags'] as $tagData) {
                            // Validasi struktur entri tag/divisi: pastikan ada 'divisi' dan 'total_postingan'
                            // Menggunakan kunci 'divisi' sesuai dengan respons API yang Anda berikan
                            if (isset($tagData['divisi'], $tagData['total_postingan'])) {
                                // Buat baris data datar untuk setiap tag/divisi
                                $flattenedData[] = [
                                    'tahun' => $tahun,
                                    'bulan' => $bulan,
                                    'divisi' => $tagData['divisi'], // Ambil nilai dari kunci 'divisi'
                                    'total_postingan' => $tagData['total_postingan'],
                                ];
                            } else {
                                // Opsional: log warning jika ada entri tag dengan format tidak terduga
                                // \Log::warning('Skipping tag entry with invalid format in API response.', ['tag_data' => $tagData, 'month_data' => $monthData]);
                            }
                        }
                    } else {
                         // Opsional: log warning jika ada entri bulan dengan format tidak terduga
                        // \Log::warning('Skipping month entry with invalid format in API response.', ['month_data' => $monthData]);
                    }
                }


                // Terapkan filtering berdasarkan parameter permintaan (tahun dan bulan) pada data yang sudah diratakan
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

                // Re-index array setelah filtering
                $filteredData = array_values($filteredData);

                // Return data to DataTable
                // Pastikan nama kolom di sini ('tahun', 'bulan', 'divisi', 'total_postingan')
                // sesuai dengan kunci yang Anda gunakan di array $flattenedData
                return DataTables::of($filteredData)
                    ->addIndexColumn() // Menambahkan kolom nomor urut
                    // Menambahkan kolom untuk DataTables, mengambil data dari kunci di array datar
                     ->addColumn('tahun', function($row) {
                         return $row['tahun'] ?? ''; // Menggunakan null coalescing untuk keamanan
                     })
                     ->addColumn('bulan', function($row) {
                         return $row['bulan'] ?? '';
                     })
                     ->addColumn('divisi', function($row) {
                         return $row['divisi'] ?? ''; // Mengambil dari kunci 'divisi'
                     })
                    ->addColumn('total_postingan', function ($row) {
                         $totalPostingan = $row['total_postingan'] ?? 0;
                        return '
                            <div class="flex text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-primary">' . $totalPostingan . '</span>
                            </div>';
                    })
                    // Tentukan kolom yang berisi HTML mentah
                    ->rawColumns(['total_postingan'])
                    ->make(true); // Buat respons DataTables
            }

            // If the API call fails, return an error response
            $statusCode = $response->status();
            $errorMessage = $response->body(); // Ambil body respons untuk detail error

            // Kembalikan respons error ke pemanggil AJAX
            return response()->json(['error' => "Error fetching data from forum API: Status {$statusCode}", 'details' => $errorMessage], $statusCode);
        }

        // If not an AJAX request, return the view
        return view('forumrecaps.monthly.index'); // Pastikan view ini ada
    }
}
