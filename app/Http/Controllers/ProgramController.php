<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Data Program Kerja";
        $submenu = "Pengelolaan Data Program Kerja";
        $bidang = Bidang::all();
        if ($request->ajax()) {
            $data = Program::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('bidang', function ($data) {
                    return $data->bidang->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> Hapus</a><center>';
                    return $btn;
                })
                ->rawColumns(['bidang', 'action'])
                ->make(true);
        }
        return view('program.data', compact('menu', 'submenu', 'bidang'));
    }
    public function store(Request $request)
    {
        //Translate Bahasa Indonesia
        $message = array(
            'bidang_id.required' => 'Bidang harus dipilih.',
            'name.required' => 'Nama Program harus diisi.',
            'name.unique' => 'Program sudah ada.',
        );
        if (!$request->hidden_id) {
            $ruleProker = 'required|unique:program_kerja,name';
        } else {
            $lastProker = Program::where('id', $request->hidden_id)->first();
            if ($lastProker->name == $request->name) {
                $ruleProker = 'required';
            } else {
                $ruleProker = 'required|unique:program_kerja,name';
            }
        }
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required',
            'name' => $ruleProker,
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        Program::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'bidang_id' => $request->bidang_id,
                'name' => $request->name,
            ]
        );
        return response()->json(['success' => 'Program Kerja saved successfully.']);
    }

    public function edit($id)
    {
        $program = Program::find($id);
        return response()->json($program);
    }

    public function destroy($id)
    {
        Program::find($id)->delete();
        return response()->json(['success' => 'Program Kerja deleted successfully.']);
    }

    public function getProgram(Request $request)
    {
        $data = Program::where("bidang_id", $request->bidang_id)->get(["id", "name"]);
        return response()->json($data);
    }
}
