<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Data Anggota";
        $submenu = "Pengelolaan Data Anggota";
        if ($request->ajax()) {
            $data = Anggota::where('user_id', Auth::user()->id)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)"  data-id="' . $row->id . '" class="edit btn btn-primary btn-xs">Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)"  data-id="' . $row->id . '"class="btn btn-danger btn-xs delete">Hapus</a><center>';
                    return $btn;
                })
                ->addColumn('sk', function ($row) {
                    return '<center><a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-success btn-xs review">Lihat SK</a></center>';
                })
                ->rawColumns(['action', 'sk'])
                ->make(true);
        }
        return view('anggota.data', compact('menu', 'submenu'));
    }
    public function detailkec(Request $request)
    {
        if ($request->ajax()) {
            $kecamatanId = $request->input('kecamatan_id');
            if ($kecamatanId !== null) {
                $data = Anggota::where('kecamatan_id', $kecamatanId)->where('desa_id', null)->latest()->get();
            } else {
                $data = [];
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)"  data-id="' . $row->id . '" class="edit btn btn-primary btn-xs">Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)"  data-id="' . $row->id . '"class="btn btn-danger btn-xs delete">Hapus</a><center>';
                    return $btn;
                })
                ->addColumn('sk', function ($row) {
                    return '<center><a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-success btn-xs review">Lihat SK</a></center>';
                })
                ->rawColumns(['sk'])
                ->make(true);
        }
        return view('anggota.jumlah_kec');
    }
    public function detaildesa(Request $request)
    {
        if ($request->ajax()) {
            $desaId = $request->input('desa_id');
            if ($desaId !== null) {
                $data = Anggota::where('desa_id', $desaId)->latest()->get();
            } else {
                $data = [];
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)"  data-id="' . $row->id . '" class="edit btn btn-primary btn-xs">Edit</a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)"  data-id="' . $row->id . '"class="btn btn-danger btn-xs delete">Hapus</a><center>';
                    return $btn;
                })
                ->addColumn('sk', function ($row) {
                    return '<center><a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-success btn-xs review">Lihat SK</a></center>';
                })
                ->rawColumns(['sk'])
                ->make(true);
        }
        return view('anggota.jumlah_desa');
    }
    public function store(Request $request)
    {
        $message = array(
            'name.required'     => 'Nama harus diisi.',
            'email.required'    => 'Email harus diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'email.email' => 'Penulisan email tidak benar.',
            'nohp.required'     => 'No. HP harus diisi.',
            'nohp.unique'       => 'No. HP sudah terdaftar.',
            'nohp.numeric'      => 'No. HP harus berupa angka.',
            'nohp.max_digits'      => 'No. HP maksimal 12 angka.',
            'nohp.min_digits'      => 'No. HP minimal 11 angka.',
            'sk.required'      => 'SK harus diupload.',
            'sk.*.mimes'      => 'Tipe Foto yang diunggah harus pdf.',
            'sk.*.max'        => 'Ukuran Foto tidak boleh lebih dari 2 MB.',
        );

        if ($request->hidden_id) {
            $ruleEmail      = 'nullable|email';
            $ruleNohp       = 'numeric|min_digits:11|max_digits:12';
        } else {
            $ruleEmail      = 'required|email|unique:anggota,email';
            $ruleNohp = 'required|min_digits:11|max_digits:12|unique:anggota,nohp|numeric';
        }

        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => $ruleEmail,
            'nohp'          => $ruleNohp,
            'sk'          => 'required|mimes:pdf|max:2048'
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request->hasFile('sk')) {
            $sk = $request->file('sk');
            $fileSk = time() . '-' . $sk->getClientOriginalName();
            $sk->storeAs('public/sk', $fileSk);
            if ($request->hidden_id) {
                $oldSk = Anggota::find($request->hidden_id);
                Storage::delete('public/sk/' . $oldSk->sk);
            }
        } elseif ($request->hidden_id) {
            $oldSk = Anggota::find($request->hidden_id);
            $fileSk = $oldSk->sk;
        }
        $kec_id = null;
        $desa_id = null;
        if (Auth::user()->kecamatan_id && !Auth::user()->desa_id) {
            $kec_id = Auth::user()->kecamatan_id;
            $desa_id = null;
        } elseif (Auth::user()->kecamatan_id && Auth::user()->desa_id) {
            $kec_id = Auth::user()->kecamatan_id;
            $desa_id = Auth::user()->desa_id;
        }
        Anggota::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'kecamatan_id'   => $kec_id,
                'desa_id'        => $desa_id,
                'user_id'        => Auth::user()->id,
                'name'           => $request->name,
                'email'          => $request->email,
                'nohp'           => $request->nohp,
                'sk'             => $fileSk,
            ]
        );
        return response()->json(['success' => 'Anggota saved successfully.']);
    }

    public function edit($id)
    {
        $data = Anggota::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data =  Anggota::find($id);
        if ($data->sk) {
            Storage::delete('public/sk/' . $data->sk);
        }
        $data->delete();
        return response()->json(['success' => 'Anggota deleted successfully.']);
    }

    public function getSKImage(Request $request)
    {
        $SkId = $request->input('sk_id');
        $sk = Anggota::find($SkId);
        if (!$sk) {
            return response()->json(['error' => 'SK not found'], 404);
        }
        $imagePath = 'storage/sk/' . $sk->sk;
        return response()->json([
            'imagePath' => $imagePath,
        ]);
    }

    public function getSK($id)
    {
        $data = Anggota::where("id", $id)->get();
        return response()->json($data);
    }

    public function JumlahAnggotaKec(Request $request)
    {
        $menu = 'Jumlah Keanggotaan Kecamatan';
        $submenu = 'Data Jumlah Keanggotaan Kecamatan';
        if ($request->ajax()) {
            $data = Kecamatan::leftJoin('users', function ($join) {
                $join->on('kecamatan.id', '=', 'users.kecamatan_id')
                    ->whereNull('users.desa_id');
            })
                ->leftJoin('anggota', 'users.id', '=', 'anggota.user_id')
                ->leftJoin('profil_users', 'users.id', '=', 'profil_users.user_id')
                ->select(
                    'kecamatan.name',
                    'users.kecamatan_id',
                    'profil_users.nama_pkk',
                    \DB::raw('COUNT(anggota.id) as jumlah_anggota')
                )
                ->groupBy('kecamatan.name', 'users.kecamatan_id', 'profil_users.nama_pkk')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kecamatan', function ($data) {
                    return $data->name;
                })
                ->addColumn('pkk', function ($data) {
                    if ($data->nama_pkk != null) {
                        $nama_pkk = $data->nama_pkk;
                    } else {
                        $nama_pkk =  '<center><span class="badge badge-danger"><i>* tidak terdefinisi</i></span></center>';
                    }
                    return $nama_pkk;
                })
                ->addColumn('jumlah', function ($data) {
                    $jumlah =  '<center><span class="badge badge-success">' . $data->jumlah_anggota . '</span></center>';
                    return $jumlah;
                })
                ->addColumn('detail', function ($row) {
                    return '<center><a href="javascript:void(0)" data-id="' . $row->kecamatan_id . '" data-name="' . $row->nama_pkk . '" class="btn btn-primary btn-xs detail">Detail</a></center>';
                })
                ->rawColumns(['pkk', 'jumlah', 'detail'])
                ->make(true);
        }

        return view('anggota.jumlah_kec', compact('menu', 'submenu'));
    }
    public function JumlahAnggotaDesa(Request $request)
    {
        $menu = 'Jumlah Keanggotaan Desa/Kelurahan';
        $submenu = 'Data Jumlah Keanggotaan Desa/Kelurahan';
        if ($request->ajax()) {
            $data = Desa::leftJoin('users', 'desa.id', '=', 'users.desa_id')
                ->leftJoin('profil_users', 'users.id', '=', 'profil_users.user_id')
                ->leftJoin('anggota', 'users.id', '=', 'anggota.user_id')
                ->select(
                    'desa.name',
                    'desa.id as desa_id',
                    \DB::raw('COUNT(anggota.id) as jumlah_anggota'),
                    'profil_users.nama_pkk'
                )
                ->groupBy('desa.name', 'desa.id', 'profil_users.nama_pkk')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('desa', function ($data) {
                    return $data->name;
                })
                ->addColumn('pkk', function ($data) {
                    if ($data->nama_pkk != null) {
                        $nama_pkk = $data->nama_pkk;
                    } else {
                        $nama_pkk =  '<center><span class="badge badge-danger"><i>* tidak terdefinisi</i></span></center>';
                    }
                    return $nama_pkk;
                })
                ->addColumn('jumlah', function ($data) {
                    $jumlah =  '<center><span class="badge badge-success">' . $data->jumlah_anggota . '</span></center>';
                    return $jumlah;
                })
                ->addColumn('detail', function ($row) {
                    return '<center><a href="javascript:void(0)" data-id="' . $row->desa_id . '" data-name="' . $row->nama_pkk . '" class="btn btn-primary btn-xs detail">Detail</a></center>';
                })
                ->rawColumns(['pkk', 'jumlah', 'detail'])
                ->make(true);
        }

        return view('anggota.jumlah_desa', compact('menu', 'submenu'));
    }
}
