<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\ProfilUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Data User";
        $submenu = "Pengelolaan Data User";
        $kecamatan = Kecamatan::all();
        if ($request->ajax()) {
            $data = User::with('kecamatan', 'desa')->where('role', '!=', 1)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function ($data) {
                    if ($data->foto) {
                        $foto = '<a href="' . url('storage/foto-users/' . $data->foto) . '" class="popup-link" target="blank">
                        <center><img src="' . url('storage/foto-users/' . $data->foto) . '" width="50px" class="img-rounded" alt="' . $data->name . '"><center>
                    </a>';
                    } else {
                        $foto = "<img class='img-rounded' src='" . asset("assets/img/blank.jpg") . "' width='50'>";
                    }
                    return $foto;
                })
                ->addColumn('kecamatan', function ($data) {
                    if ($data->kecamatan_id == null) {
                        return "-";
                    } else {
                        return $data->kecamatan->name;
                    }
                })
                ->addColumn('desa', function ($data) {
                    if ($data->desa_id == null) {
                        return "-";
                    } else {
                        return $data->desa->name;
                    }
                })
                ->addColumn('role', function ($data) {
                    if ($data->role == 1) {
                        $role = '<center><span class="badge badge-info">Administrator</span></center>';
                    } elseif ($data->role == 2) {
                        $role = '<center><span class="badge badge-info">Admin Kecamatan</span></center>';
                    } else {
                        $role = '<center><span class="badge badge-info">Admin Desa</span></center>';
                    }
                    return $role;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)"  data-id="' . $row->id . '" class="edit btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)"  data-id="' . $row->id . '"class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a><center>';
                    return $btn;
                })
                ->rawColumns(['foto', 'role', 'action'])
                ->make(true);
        }
        return view('user.data', compact('menu', 'submenu', 'kecamatan'));
    }
    public function store(Request $request)
    {
        $message = array(
            'role.required'    => 'Level harus dipilih terlebih dahulu.',
            'kecamatan_id.required' => 'Kecamatan harus dipilih.',
            'kecamatan_id.unique' => 'User pada kecamatan ini sudah ada, pilih kecamatan lain.',
            'desa_id.required' => 'Desa/Kelurahan harus dipilih.',
            'desa_id.unique' => 'User pada Desa/Kelurahan ini sudah ada, pilih kecamatan lain.',
            'name.required'     => 'Nama harus diisi.',
            'email.required'    => 'Email harus diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'email.email' => 'Penulisan email tidak benar.',
            'nohp.required'     => 'No. HP harus diisi.',
            'nohp.unique'       => 'No. HP sudah terdaftar.',
            'nohp.numeric'      => 'No. HP harus berupa angka.',
            'nohp.max_digits'      => 'No. HP maksimal 12 angka.',
            'nohp.min_digits'      => 'No. HP minimal 11 angka.',
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
            $role           = 'required';
            $ruleEmail      = 'nullable|email';
            $ruleNohp       = 'numeric|min_digits:11|max_digits:12';
            $rulePassword   = 'nullable|min:8';
            $ruleRePassword   = 'nullable|same:password|min:8';
            $data = User::where('id', $request->hidden_id)->first();
            if ($data->desa_id == null) {
                $ruleDesa = 'nullable';
            } else {
                if ($data->desa_id == $request->desa_id) {
                    $ruleDesa = 'required';
                } else {
                    $ruleDesa = 'required|unique:users,desa_id';
                }
            }
            if ($data->kecamatan_id == $request->kecamatan_id) {
                $ruleKecamatan = 'required';
            } else {
                $ruleKecamatan = 'required';
            }
        } else {
            if ($request->role == null || $request->role == "1") {
                $role  = 'required';
                $ruleKecamatan  = 'nullable';
                $ruleDesa       = 'nullable';
            } elseif ($request->role == "2") {
                $role  = 'required';
                $ruleKecamatan  = 'required';
                $ruleDesa       = 'nullable';
            } elseif ($request->role == "3") {
                $role  = 'required';
                $ruleKecamatan  = 'required';
                $ruleDesa       = 'required|unique:users,desa_id';
            }
            $ruleEmail      = 'required|email|unique:users,email';
            $ruleNohp       = 'required|min_digits:11|max_digits:12|unique:users,nohp|numeric';
            $rulePassword   = 'required|min:8';
            $ruleRePassword = 'required|same:password|min:8';
        }

        $validator = Validator::make($request->all(), [
            'role'          =>  $role,
            'kecamatan_id'  =>  $ruleKecamatan,
            'desa_id'       =>  $ruleDesa,
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
                $oldFoto = User::find($request->hidden_id);
                Storage::delete('public/foto-users/' . $oldFoto->foto);
            }
        } elseif ($request->hidden_id) {
            $oldFoto = User::find($request->hidden_id);
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
                $oldPassword = User::find($request->hidden_id);
                $password = $oldPassword->password;
            }
        } else {
            $password = null;
        }

        $usersid = User::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'kecamatan_id' => $request->kecamatan_id,
                'desa_id'      => $request->desa_id,
                'name'      => $request->name,
                'email'     => $request->email,
                'nohp'      => $request->nohp,
                'role'      => $request->role,
                'password'  => $password,
                'foto'      => $fileFoto,
            ]
        );

        ProfilUser::updateOrCreate(
            [
                'user_id' => $request->hidden_id
            ],
            [
                'user_id' => $usersid->id,
            ]
        );

        return response()->json(['success' => 'Operator saved successfully.']);
    }

    public function edit($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data =  User::find($id);
        if ($data->foto) {
            Storage::delete('public/foto-users/' . $data->foto);
        }
        $data->delete();
        return response()->json(['success' => 'Operator deleted successfully.']);
    }

    public function profil()
    {
        $menu = "Profil";
        $user = User::with('profil')->where('id', Auth::user()->id)->first();
        return view('user.profil', compact('menu', 'user'));
    }

    public function profiledit()
    {
        $data = ProfilUser::find(auth()->user()->id);
        return response()->json($data);
    }

    public function updatepassword(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        //Translate Bahasa Indonesia
        $message = array(
            'old_password' => 'Password Lama harus diisi.',
            'password.required' => 'Password harus diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.min'      => 'Password minimal 8 karakter.',
            'repassword.required' => 'Harap konfirmasi password.',
            'repassword.same' => 'Password harus sama.',
            'repassword.min' => 'Password minimal 8 karakter.',
        );

        $validator = Validator::make($request->all(), [
            'old_password'      => 'required',
            'password'      => 'required|min:8',
            'repassword'    => 'required|same:password|min:8',
        ], $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if (Hash::check($request->old_password, auth()->user()->password)) {
            $user->update([
                'password' => $request->password,
            ]);
            return response()->json(['success' => 'Password changed successfully']);
        } else {
            return response()->json(['error' => 'Password Lama salah'], 422);
        }
    }

    public function updatefoto(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        //Translate Bahasa Indonesia
        $message = array(
            'foto.required' => 'Foto harus dipilih.',
            'foto.image' => 'Foto harus image.',
            'foto.mimes' => 'Foto harus jpeg,png,jpg.',
            'foto,max' => 'Foto maksimal 2MB.',
        );
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $img = $request->file('foto');
        if ($img != null) {
            $fileFoto = time() . '-' . $img->getClientOriginalName();
            $img->storeAs('public/foto-users', $fileFoto);
            Storage::delete('public/foto-users/' . $user->foto);
        }
        $user->update([
            'foto' => $fileFoto,
        ]);
        return response()->json(['success' => 'Foto updated successfully.']);
    }
    public function updatebanner(Request $request, $id)
    {
        $profil = ProfilUser::where('id', $id)->first();
        //Translate Bahasa Indonesia
        $message = array(
            'banner.required' => 'Banner harus dipilih.',
            'banner.image' => 'Banner harus image.',
            'banner.mimes' => 'Banner harus jpeg,png,jpg.',
            'banner.max' => 'Banner maksimal 2MB.',
            'banner.dimensions' => 'Ukuran Banner harus 1920 x 720 pixel.',
        );
        $validator = Validator::make($request->all(), [
            'banner' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:width=1920,height=720'
        ], $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $img = $request->file('banner');
        if ($img != null) {
            $fileBanner = time() . '-' . $img->getClientOriginalName();
            $img->storeAs('public/foto-banner', $fileBanner);
            Storage::delete('public/foto-banner/' . $profil->banner);
        }
        $profil->update([
            'banner' => $fileBanner,
        ]);
        return response()->json(['success' => 'Banner updated successfully.']);
    }

    public function updateprofil(Request $request, $id)
    {
        $profil = ProfilUser::where('id', $id)->first();
        //Translate Bahasa Indonesia
        $message = array(
            'nohp_kantor.max' => 'HP maksimal harus 12 digit.',
            'nohp_kantor.min' => 'HP minimal harus 11 digit.',
        );
        $validator = Validator::make($request->all(), [
            'nohp_kantor'      => 'max:12|min:11',
        ], $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $profil->update([
            'nama_pkk' => $request->nama_pkk,
            'nohp_kantor' => $request->nohp_kantor,
            'alamat_kantor' => $request->alamat_kantor,
        ]);
        return response()->json(['success' => 'Profil changed successfully']);
    }
}
