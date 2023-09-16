<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\UserKecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserKecController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Data User Kecamatan";
        $submenu = "Pengelolaan Data User Kecamata";
        $kecamatan = Kecamatan::all();
        if ($request->ajax()) {
            $data = UserKecamatan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function ($data) {
                    if ($data->foto) {
                        $foto = 'storage/foto-users/' . $data->foto;
                    } else {
                        $foto = "assets/img/blank.jpg";
                    }
                    return "<img class='img-rounded' src='" . asset($foto) . "' width='50'>";
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('email', function ($data) {
                    return $data->email;
                })
                ->addColumn('nohp', function ($data) {
                    return $data->nohp;
                })
                ->addColumn('kecamatan', function ($data) {
                    return $data->kecamatan->name;
                })
                ->addColumn('role', function ($data) {
                    if ($data->role == 2) {
                        $role = '<center><span class="badge badge-info">Admin Kecamatan</span></center>';
                    }
                    return $role;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> Hapus</a><center>';
                    return $btn;
                })
                ->rawColumns(['foto', 'role', 'kecamatan', 'action'])
                ->make(true);
        }
        return view('user-kecamatan.data', compact('menu', 'submenu', 'kecamatan'));
    }
    public function store(Request $request)
    {
        $message = array(
            'kecamatan_id.required' => 'Kecamatan harus dipilih.',
            'kecamatan_id.unique' => 'User pada kecamatan ini sudah ada, pilih kecamatan lain.',
            'name.required'     => 'Nama harus diisi.',
            'email.required'    => 'Email harus diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'nohp.required'     => 'No. HP harus diisi.',
            'nohp.unique'       => 'No. HP sudah terdaftar.',
            'nohp.numeric'      => 'No. HP harus berupa angka.',
            'password.required' => 'Password harus diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.min'      => 'Password minimal 8 karakter.',
            'repassword.required' => 'Harap konfirmasi password.',
            'repassword.same' => 'Password harus sama.',
            'repassword.min' => 'Password minimal 8 karakter.',
            'foto.*.mimes'      => 'Tipe foto yang diunggah harus jpg, jpeg atau png.',
            'foto.*.max'        => 'Ukuran foto tidak boleh lebih dari 2 MB.',
        );

        if ($request->hidden_id) {
            $ruleEmail      = 'nullable';
            $ruleNohp       = 'numeric';
            $rulePassword   = 'nullable|min:8';
            $ruleRePassword   = 'nullable|same:password|min:8';
            $lastKec = UserKecamatan::where('id', $request->hidden_id)->first();
            if ($lastKec->kecamatan_id == $request->kecamatan_id) {
                $ruleKecamatan = 'required';
            } else {
                $ruleKecamatan = 'required|unique:users_kecamatan,kecamatan_id';
            }
        } else {
            $ruleKecamatan = 'required|unique:users_kecamatan,kecamatan_id';
            $ruleEmail      = 'required|unique:users_kecamatan,email';
            $ruleNohp       = 'required|unique:users_kecamatan,nohp|numeric';
            $rulePassword   = 'required|min:8';
            $ruleRePassword   = 'required|same:password|min:8';
        }

        $validator = Validator::make($request->all(), [
            'kecamatan_id'  =>  $ruleKecamatan,
            'name'          => 'required',
            'email'         => $ruleEmail,
            'nohp'          => $ruleNohp,
            'password'      => $rulePassword,
            'repassword'    => $ruleRePassword,
            'foto'          => 'mimes:jpg,jpeg,png|max:8048'
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fileFoto = time() . '-' . $foto->getClientOriginalName();
            $foto->storeAs('public/foto-users', $fileFoto);
            if ($request->hidden_id) {
                $oldFoto = UserKecamatan::find($request->hidden_id);
                Storage::delete('public/foto-users/' . $oldFoto->foto);
            }
        } elseif ($request->hidden_id) {
            $oldFoto = UserKecamatan::find($request->hidden_id);
            $fileFoto = $oldFoto->foto;
        } else {
            $fileFoto = null;
        }

        if ($request->filled('password')) {
            $password = $request->password;
        } elseif ($request->hidden_id) {
            if ($request->filled('password')) {
                $password = $request->password;
            } else {
                $oldPassword = UserKecamatan::find($request->hidden_id);
                $password = $oldPassword->password;
            }
        } else {
            $password = null;
        }

        UserKecamatan::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'kecamatan_id' => $request->kecamatan_id,
                'name'      => $request->name,
                'email'     => $request->email,
                'nohp'      => $request->nohp,
                'role'      => 2,
                'password'  => $password,
                'foto'      => $fileFoto,
            ]
        );

        return response()->json(['success' => 'User Kecamatan saved successfully.']);
    }
    public function edit($id)
    {
        $data = UserKecamatan::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data =  UserKecamatan::find($id);
        if ($data->foto) {
            Storage::delete('public/foto-users/' . $data->foto);
        }
        $data->delete();
        return response()->json(['success' => 'User Kecamatan deleted successfully.']);
    }
}
