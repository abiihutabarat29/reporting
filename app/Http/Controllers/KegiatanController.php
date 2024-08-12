<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Data Kegiatan";
        $submenu = "Pengelolaan Data Kegiatan";
        $program = Program::all();
        if ($request->ajax()) {
            $data = Kegiatan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('program', function ($data) {
                    return $data->program->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> Hapus</a><center>';
                    return $btn;
                })
                ->rawColumns(['program', 'action'])
                ->make(true);
        }
        return view('kegiatan.data', compact('menu', 'submenu', 'program'));
    }
    public function store(Request $request)
    {
        //Translate Bahasa Indonesia
        $message = array(
            'program_id.required' => 'Program harus dipilih.',
            'name.required' => 'Nama Kegiatan harus diisi.',
            'name.unique' => 'Kegiatan sudah ada.',
        );
        if (!$request->hidden_id) {
            $ruleKegiatan = 'required|unique:kegiatan,name';
        } else {
            $lastKegiatan = Kegiatan::where('id', $request->hidden_id)->first();
            if ($lastKegiatan->name == $request->name) {
                $ruleKegiatan = 'required';
            } else {
                $ruleKegiatan = 'required|unique:kegiatan,name';
            }
        }
        $validator = Validator::make($request->all(), [
            'program_id' => 'required',
            'name' => $ruleKegiatan,
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        Kegiatan::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'program_id' => $request->program_id,
                'name' => $request->name,
            ]
        );
        return response()->json(['success' => 'Kegiatan saved successfully.']);
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::find($id);
        return response()->json($kegiatan);
    }

    public function destroy($id)
    {
        Kegiatan::find($id)->delete();
        return response()->json(['success' => 'Kegiatan deleted successfully.']);
    }

    public function getKegiatan(Request $request)
    {
        $data = Kegiatan::where("program_id", $request->program_id)->get(["id", "name"]);
        return response()->json($data);
    }
}
