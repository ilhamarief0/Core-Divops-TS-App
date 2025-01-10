<?php

namespace App\Http\Controllers;

use App\Models\ClientMonitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ClientMonitoringWebController extends Controller
{

    public function dataTable(Request $request)
    {
        if ($request->ajax()) {

            $data = ClientMonitoring::query();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('name', function ($row) {
                    return '<a class="text-gray-800 text-hover-primary mb-12">' . $row->name . '</a>';
                })
                ->addColumn('bot_token', function ($row) {
                    return '<span class="badge badge-light-secondary">' . $row->bot_token . '</span>';
                })
                ->addColumn('chat_id', function ($row) {
                    return '<span class="badge badge-light-primary">' . $row->chat_id . '</span>';
                })


                ->addColumn('action', function ($row) {
                    $editBtn = '
                    <a href="javascript:void(0)" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_client">
                         <button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">
                             <i class="ki-duotone ki-setting-3 fs-3">
                                 <span class="path1"></span>
                                 <span class="path2"></span>
                                 <span class="path3"></span>
                                 <span class="path4"></span>
                                 <span class="path5"></span>
                             </i>
                         </button>
                   </a>

                     ';

                    $deleteBtn = '
                     <button class="btn btn-icon btn-active-light-primary w-30px h-30px delete-customer" data-id="' . $row->id . '" data-name="' . $row->name . '">
                     <i class="ki-duotone ki-trash fs-3">
                         <span class="path1"></span>
                         <span class="path2"></span>
                         <span class="path3"></span>
                         <span class="path4"></span>
                         <span class="path5"></span>
                     </i>
                     </button>

                     ';

                    $layout = '
                    <div class="flex text-end">
                    ' . $editBtn . $deleteBtn . '
                    </div>';


                    return $layout;
                })
                ->rawColumns(['name', 'bot_token', 'action', 'chat_id'])
                ->make(true);
        }
    }

    public function index()
    {
        return view('monitoringweb.client.list');
    }


    public function store(Request $request)
    {

        // dd($request->all());

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:60',
            'description' => 'nullable|string',
            'bot_token' => 'required|max:255',
            'chat_id' => 'nullable',
        ]);

        // Add the authenticated user's ID as the owner_id
        $validatedData['creator_id'] = auth()->id();

        // Create a new ClientMonitoring record with the validated data
        $customerSite = ClientMonitoring::create($validatedData);

        return redirect()->back();
    }

    public function delete($id)
    {
        // Temukan user berdasarkan ID atau gagal dengan notifikasi error
        $client = ClientMonitoring::findOrFail($id);
        // Hapus user
        $client->delete();
        // Kembalikan response sukses
        return response()->json(['message' => 'Client deleted successfully'], 200);
    }

    public function getData(ClientMonitoring $id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Client',
            'data'    => $id
        ]);
    }

    public function update(Request $request, ClientMonitoring $id)
    {

        // Validate incoming data
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'bot_token' => 'required',
            'chat_id' => 'required',
        ]);


        // Update the customer site with validated data
        $id->update($validatedData);

        return response()->json(['message' => 'Client Updated successfully'], 200);
    }
}
