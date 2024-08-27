<?php

namespace App\Http\Controllers;

use App\Models\WeeklyRecapForum;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WeeklyRecapsForumController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Mulai query dari model WeeklyRecapForum
            $data = WeeklyRecapForum::query();

            // Terapkan filter berdasarkan minggu, bulan, dan tahun
            if ($request->has('minggu') && $request->minggu) {
                $data->where('minggu', $request->minggu);
            }
            if ($request->has('bulan') && $request->bulan) {
                $data->where('bulan', $request->bulan);
            }
            if ($request->has('tahun') && $request->tahun) {
                $data->where('tahun', $request->tahun);
            }

            // Return data ke DataTable
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('total_postingan', function ($row) {
                    $layout = '
                        <div class="flex text-end">
                            <span class="badge py-3 px-4 fs-7 badge-light-primary">' . $row->total_postingan . '</span>
                        </div>';
                    return $layout;
                })
                ->rawColumns(['total_postingan'])
                ->make(true);
        }

        return view('forumrecaps.weekly.index');
    }
}
