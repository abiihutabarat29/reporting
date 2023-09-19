<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KecamatanController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Data Kecamatan";
        $submenu = "Pengelolaan Data Kecamatan";
        if ($request->ajax()) {
            $data = Kecamatan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_wilayah', function ($data) {
                    return $data->kode_wilayah;
                })
                ->addColumn('kecamatan', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> Hapus</a><center>';
                    return $btn;
                })
                ->rawColumns(['kode_wilayah', 'kecamatan', 'action'])
                ->make(true);
        }
        return view('kecamatan.data', compact('menu', 'submenu'));
    }
    public function store(Request $request)
    {
        //Translate Bahasa Indonesia
        $message = array(
            'name.required' => 'Nama Kecamatan harus diisi.',
            'kode_wilayah.unique' => 'Kode Wilayah sudah terdaftar.',
            'kode_wilayah.required' => 'Kode Wilayah harus diisi.',
            'kode_wilayah.max' => 'Kode Wilayah maksimal 6 digit.',
            'kode_wilayah.min' => 'Kode Wilayah minimal 6 digit.',
        );
        if (!$request->hidden_id) {
            $ruleKode = 'required|max:6|min:6|unique:kecamatan,kode_wilayah';
        } else {
            $lastKode = Kecamatan::where('id', $request->hidden_id)->first();
            if ($lastKode->kode_wilayah == $request->kode_wilayah) {
                $ruleKode = 'required|max:6|min:6';
            } else {
                $ruleKode = 'required|max:6|min:6|unique:kecamatan,kode_wilayah';
            }
        }
        $validator = Validator::make($request->all(), [
            'kode_wilayah' => $ruleKode,
            'name' => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        Kecamatan::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'kode_wilayah' => $request->kode_wilayah,
                'name' => $request->name,
            ]
        );
        return response()->json(['success' => 'Kecamatan saved successfully.']);
    }

    public function edit($id)
    {
        $kecamatan = Kecamatan::find($id);
        return response()->json($kecamatan);
    }

    public function getKecamatan()
    {
        $kecamatan = Kecamatan::get(["kecamatan", "id"]);
        return response()->json($kecamatan);
    }

    public function destroy($id)
    {
        Kecamatan::find($id)->delete();
        return response()->json(['success' => 'Kecamatan deleted successfully.']);
    }
}
