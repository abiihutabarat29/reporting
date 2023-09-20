<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BidangController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Data Bidang";
        $submenu = "Pengelolaan Data Bidang";
        if ($request->ajax()) {
            $data = Bidang::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> Hapus</a><center>';
                    return $btn;
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }
        return view('bidang.data', compact('menu', 'submenu'));
    }
    public function store(Request $request)
    {
        //Translate Bahasa Indonesia
        $message = array(
            'name.required' => 'Nama Bidang harus diisi.',
            'name.unique' => 'Bidang sudah ada.',
        );
        if (!$request->hidden_id) {
            $ruleBidang = 'required|unique:bidang,name';
        } else {
            $lastBidang = Bidang::where('id', $request->hidden_id)->first();
            if ($lastBidang->name == $request->name) {
                $ruleBidang = 'required';
            } else {
                $ruleBidang = 'required|unique:bidang,name';
            }
        }
        $validator = Validator::make($request->all(), [
            'name' => $ruleBidang,
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        Bidang::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'name' => $request->name,
            ]
        );
        return response()->json(['success' => 'Bidang saved successfully.']);
    }

    public function edit($id)
    {
        $bidang = Bidang::find($id);
        return response()->json($bidang);
    }

    public function getBidang()
    {
        $bidang = Bidang::get(["name", "id"]);
        return response()->json($bidang);
    }

    public function destroy($id)
    {
        Bidang::find($id)->delete();
        return response()->json(['success' => 'Bidang deleted successfully.']);
    }
}
