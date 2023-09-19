<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Data Desa/Kelurahan";
        $submenu = "Pengelolaan Data Desa/Kelurahan";
        $kecamatan = Kecamatan::all();
        if ($request->ajax()) {
            $data = Desa::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_wilayah', function ($data) {
                    return $data->kode_wilayah;
                })
                ->addColumn('desa', function ($data) {
                    return $data->name;
                })
                ->addColumn('kecamatan', function ($data) {
                    return $data->kecamatan->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)"  data-id="' . $row->id . '" class="edit btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> Hapus</a><center>';
                    return $btn;
                })
                ->rawColumns(['kode_wilayah', 'kecamatan', 'action'])
                ->make(true);
        }
        return view('desa.data', compact('menu', 'submenu', 'kecamatan'));
    }
    public function store(Request $request)
    {
        //Translate Bahasa Indonesia
        $message = array(
            'kecamatan_id.required' => 'Kecamatan harus dipilih.',
            'name.required' => 'Nama Desa harus diisi.',
            'kode_wilayah.required' => 'Kode Wilayah harus diisi.',
            'kode_wilayah.max' => 'Kode Wilayah maksimal 10 digit.',
            'kode_wilayah.min' => 'Kode Wilayah minimal 10 digit.',
        );
        $validator = Validator::make($request->all(), [
            'kecamatan_id' => 'required',
            'kode_wilayah' => 'required|max:10|min:10',
            'name' => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        Desa::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'kecamatan_id' => $request->kecamatan_id,
                'kode_wilayah' => $request->kode_wilayah,
                'name' => $request->name,
            ]
        );
        return response()->json(['success' => 'Desa saved successfully.']);
    }

    public function edit($id)
    {
        $desa = Desa::find($id);
        return response()->json($desa);
    }

    public function destroy($id)
    {
        Desa::find($id)->delete();
        return response()->json(['success' => 'Desa deleted successfully.']);
    }

    public function getDesa(Request $request)
    {
        $data = Desa::where("kecamatan_id", $request->kecamatan_id)->get(["kode_wilayah", "name", "id"]);
        return response()->json($data);
    }
}
