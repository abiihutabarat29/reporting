<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Laporan;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $menu = "Laporan Kegiatan";
        $submenu = "Pengelolaan Laporan Kegiatan";
        $bidang = Bidang::all();
        if ($request->ajax()) {
            $data = Laporan::where('user_id', Auth::user()->id)->latest();
            if ($request->filled('bidang_id')) {
                $data->where(function ($query) use ($request) {
                    $query->where('bidang_id', $request->bidang_id);
                });
            }

            if ($request->filled('program_id')) {
                $data->where(function ($query) use ($request) {
                    $query->where('program_id', $request->program_id);
                });
            }
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('bidang', function ($data) {
                    return $data->bidang->name;
                })
                ->addColumn('program', function ($data) {
                    return $data->program->name;
                })
                ->addColumn('kegiatan', function ($data) {
                    return $data->kegiatan->name;
                })
                ->addColumn('date', function ($data) {
                    return $data->date;
                })
                ->addColumn('foto', function ($data) {
                    if ($data->foto) {
                        $foto = '<a href="' . url('storage/foto-kegiatan/' . $data->foto) . '" class="popup-link" target="blank">
                        <center><img src="' . url('storage/foto-kegiatan/' . $data->foto) . '" width="70px" class="img-rounded" alt="' . $data->name . '"><center>
                    </a>';
                    } else {
                        $foto = "assets/img/images.png";
                    }
                    return $foto;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>';
                    $btn = '<center>' . $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a><center>';
                    return $btn;
                })
                ->rawColumns(['bidang', 'program', 'kegiatan', 'foto', 'action'])
                ->make(true);
        }
        return view('laporan.data', compact('menu', 'submenu', 'bidang'));
    }

    public function lapdetailkec(Request $request)
    {
        if ($request->ajax()) {

            $kecamatanId = $request->input('kecamatan_id');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');

            $query = Laporan::where('kecamatan_id', $kecamatanId)
                ->where('desa_id', null);

            if ($date_start && $date_end) {
                $query->whereBetween('date', [$date_start, $date_end]);
            }

            $data = $query->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('bidang', function ($data) {
                    return $data->bidang->name;
                })
                ->addColumn('program', function ($data) {
                    return $data->program->name;
                })
                ->addColumn('kegiatan', function ($data) {
                    return $data->kegiatan->name;
                })
                ->addColumn('date', function ($data) {
                    return $data->date;
                })
                ->addColumn('action', function ($data) {
                    $btn = '';

                    if ($data->foto) {
                        $btn .= '<a href="' . url('storage/foto-kegiatan/' . $data->foto) . '" class="popup-link" target="_blank">
                                    <button class="btn btn-success btn-xs">Lihat</button>
                                </a>';
                    } else {
                        $btn .= '<img src="assets/img/images.png" alt="No Image" class="img-thumbnail" width="50">';
                    }

                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-xs delete">Hapus</a>';

                    return '<center>' . $btn . '</center>';
                })
                ->rawColumns(['bidang', 'program', 'kegiatan', 'action'])
                ->make(true);
        }
        return view('ranking.kecamatan');
    }

    public function lapdetaildesa(Request $request)
    {
        if ($request->ajax()) {

            $desaId = $request->input('desa_id');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');

            $query = Laporan::where('desa_id', $desaId);

            if ($date_start && $date_end) {
                $query->whereBetween('date', [$date_start, $date_end]);
            }

            $data = $query->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('bidang', function ($data) {
                    return $data->bidang->name;
                })
                ->addColumn('program', function ($data) {
                    return $data->program->name;
                })
                ->addColumn('kegiatan', function ($data) {
                    return $data->kegiatan->name;
                })
                ->addColumn('date', function ($data) {
                    return $data->date;
                })
                ->addColumn('action', function ($data) {
                    $btn = '';

                    if ($data->foto) {
                        $btn .= '<a href="' . url('storage/foto-kegiatan/' . $data->foto) . '" class="popup-link" target="_blank">
                                    <button class="btn btn-success btn-xs">Lihat</button>
                                </a>';
                    } else {
                        $btn .= '<img src="assets/img/images.png" alt="No Image" class="img-thumbnail" width="50">';
                    }

                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-xs delete">Hapus</a>';

                    return '<center>' . $btn . '</center>';
                })
                ->rawColumns(['bidang', 'program', 'kegiatan', 'action'])
                ->make(true);
        }
        return view('ranking.desa');
    }

    private function getExistingCount($date)
    {
        return Laporan::whereDate('date', $date)
            ->where('user_id', Auth::user()->id)
            ->count();
    }

    public function store(Request $request)
    {
        $message = array(
            'name.required'         => 'Judul Kegiatan harus diisi.',
            'bidang_id.required'    => 'Bidang harus dipilih.',
            'program_id.required'   => 'Program Kerja harus dipilih.',
            'kegiatan_id.required'  => 'Kegiatan harus dipilih.',
            'description.required'  => 'Deskripsi harus diisi.',
            'description.min'       => 'Deskripsi minimal 255 karakter.',
            'foto.required'         => 'Foto dokumentasi harus diupload.',
            'foto.image'            => 'Upload harus gambar.',
            'foto.mimes'            => 'Tipe foto yang diunggah harus jpg, jpeg atau png.',
            'foto.max'              => 'Ukuran foto maksimal 2MB.',
            'date.required'         => 'Tanggal kegiatan harus diisi.',
        );

        if ($request->hidden_id) {
            $ruleFoto      = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        } else {
            $ruleFoto      = 'nullable|required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string',
            'bidang_id'     => 'required',
            'program_id'    => 'required',
            'kegiatan_id'   => 'required',
            'description'   => 'required|min:255',
            'foto'          => $ruleFoto,
            'date'          => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $existingCount = $this->getExistingCount($request->date);

        if ($existingCount >= 4) {
            return response()->json(['errors' => ['Kegiatan maksimal 4 kali dalam setiap hari normalnya.']]);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fileFoto = time() . '-' . $foto->getClientOriginalName();
            $foto->storeAs('public/foto-kegiatan', $fileFoto);
            if ($request->hidden_id) {
                $oldFoto = Laporan::find($request->hidden_id);
                Storage::delete('public/foto-kegiatan/' . $oldFoto->foto);
            }
        } elseif ($request->hidden_id) {
            $oldFoto = Laporan::find($request->hidden_id);
            $fileFoto = $oldFoto->foto;
        } else {
            $fileFoto = null;
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

        Laporan::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'kecamatan_id' => $kec_id,
                'desa_id'      => $desa_id,
                'name'         => $request->name,
                'user_id'      => Auth::user()->id,
                'bidang_id'    => $request->bidang_id,
                'program_id'   => $request->program_id,
                'kegiatan_id'  => $request->kegiatan_id,
                'description'  => $request->description,
                'foto'         => $fileFoto,
                'date'         => $request->date,
            ]
        );
        return response()->json(['success' => 'Laporan Kegiatan saved successfully.']);
    }

    public function edit($id)
    {
        $data = Laporan::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data =  Laporan::find($id);
        if ($data->foto) {
            Storage::delete('public/foto-kegiatan/' . $data->foto);
        }
        $data->delete();
        return response()->json(['success' => 'Laporan Kegiatan deleted successfully.']);
    }

    public function laporankab()
    {
        $menu = 'Daftar 20 Kegiatan Kabupaten Terbaru';
        $data = Laporan::select('laporan.*', 'profil_users.nama_pkk')
            ->join('profil_users', 'laporan.user_id', '=', 'profil_users.user_id')
            ->where('laporan.kecamatan_id', '=', null)
            ->where('laporan.desa_id', '=', null)
            ->limit(20)->latest()
            ->get();
        return view('laporan.kabupaten', compact('menu', 'data'));
    }

    public function laporankec()
    {
        $menu = 'Daftar 20 Kegiatan Kecamatan Terbaru';
        $data = Laporan::select('laporan.*', 'profil_users.nama_pkk')
            ->join('profil_users', 'laporan.user_id', '=', 'profil_users.user_id')
            ->where('laporan.kecamatan_id', '!=', null)
            ->where('laporan.desa_id', '=', null)
            ->limit(20)->latest()
            ->get();
        return view('laporan.kecamatan', compact('menu', 'data'));
    }

    public function laporandesa()
    {
        $menu = 'Daftar 20 Kegiatan Desa/Kelurahan Terbaru';
        $data = Laporan::select('laporan.*', 'profil_users.nama_pkk')
            ->join('profil_users', 'laporan.user_id', '=', 'profil_users.user_id')
            ->where('laporan.kecamatan_id', '!=', null)
            ->where('laporan.desa_id', '!=', null)
            ->limit(20)->latest()
            ->get();
        return view('laporan.desa', compact('menu', 'data'));
    }

    public function pdf(Request $request)
    {
        $filterBidang = $request->input('filter_bidang');
        $filterProgram = $request->input('filter_program');

        $query = Laporan::with('kecamatan', 'desa', 'bidang', 'program', 'kegiatan', 'user')
            ->where('user_id', Auth::user()->id);

        $bidangName = null;
        $programName = null;

        if (!empty($filterBidang)) {
            $query->where('bidang_id', $filterBidang);
            $bidangName = Bidang::find($filterBidang)->name ?? 'Semua Bidang';
        }

        if (!empty($filterProgram)) {
            $query->where('program_id', $filterProgram);
            $programName = Program::find($filterProgram)->name ?? 'Semua Program';
        }

        $pdfContent = '';
        $no = 1;

        $query->orderBy('id', 'DESC')->chunk(50, function ($laporanChunk) use (&$pdfContent, $bidangName, $programName, &$no) {
            $chunkHtml = view('laporan.export', [
                'data' => $laporanChunk,
                'bidangName' => $bidangName,
                'programName' => $programName,
                'no' => $no
            ])->render();

            $pdfContent .= $chunkHtml;

            $no += count($laporanChunk);
        });

        $pdf = PDF::loadHtml($pdfContent)->setPaper('a4', 'landscape');

        $pdfFileName = time() . '_Laporan_Kegiatan.pdf';
        return $pdf->download($pdfFileName);
    }

    public function rankingkec(Request $request)
    {
        $menu = 'Ranking Kecamatan';
        $submenu = 'Data Ranking Kecamatan';
        if ($request->ajax()) {
            $query = Kecamatan::leftJoin('users', function ($join) {
                $join->on('kecamatan.id', '=', 'users.kecamatan_id')
                    ->whereNull('users.desa_id');
            })
                ->leftJoin('laporan', 'users.id', '=', 'laporan.user_id')
                ->leftJoin('profil_users', 'users.id', '=', 'profil_users.user_id')
                ->select(
                    'kecamatan.name',
                    'users.kecamatan_id',
                    'profil_users.nama_pkk',
                    \DB::raw('COUNT(laporan.id) as jumlah')
                )
                ->groupBy('kecamatan.name', 'users.kecamatan_id', 'profil_users.nama_pkk');

            if ($request->date_start && $request->date_end) {
                $query->whereBetween('laporan.date', [$request->date_start, $request->date_end]);
            }

            $data = $query->orderBy('jumlah', 'desc')->get();

            $data->each(function ($item, $key) use ($data) {
                $item->peringkat = $data->search(function ($searchItem) use ($item) {
                    return $searchItem->jumlah == $item->jumlah;
                }) + 1;
            });

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
                    $jumlah =  '<center><span class="badge badge-danger">' . $data->jumlah . '</span></center>';
                    return $jumlah;
                })
                ->addColumn('peringkat', function ($data) {
                    $badgeClass = 'badge-secondary';
                    $icon = '';
                    if ($data->peringkat == 1) {
                        $badgeClass = 'badge-success';
                        $icon = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
                    } elseif ($data->peringkat == 2) {
                        $badgeClass = 'badge-warning';
                        $icon = '<i class="fa fa-star"></i><i class="fa fa-star"></i>';
                    } elseif ($data->peringkat == 3) {
                        $icon = '<i class="fa fa-star"></i>';
                        $badgeClass = 'badge-danger';
                    }
                    $peringkat = '<center><span class="badge badge-lg ' . $badgeClass . '">' . $icon . ' Ranking : ' . $data->peringkat . '</span></center>';
                    return $peringkat;
                })
                ->addColumn('detail', function ($row) use ($request) {
                    return '<center><a href="javascript:void(0)" data-id="' . $row->kecamatan_id . '" data-name="' . $row->nama_pkk . '" data-date-start="' . $request->date_start . '" data-date-end="' . $request->date_end . '" class="btn btn-primary btn-xs detail">Detail</a></center>';
                })
                ->rawColumns(['pkk', 'jumlah', 'peringkat', 'detail'])
                ->make(true);
        }

        return view('ranking.kecamatan', compact('menu', 'submenu'));
    }

    public function rankingdesa(Request $request)
    {
        $menu = 'Ranking Desa/Kelurahan';
        $submenu = 'Data Ranking Desa/Kelurahan';
        if ($request->ajax()) {
            $query = Desa::leftJoin('users', 'desa.id', '=', 'users.desa_id')
                ->leftJoin('laporan', 'users.id', '=', 'laporan.user_id')
                ->leftJoin('profil_users', 'users.id', '=', 'profil_users.user_id')
                ->select(
                    'desa.name',
                    'desa.id as desa_id',
                    'profil_users.nama_pkk',
                    \DB::raw('COUNT(laporan.id) as jumlah')
                )
                ->groupBy('desa.name', 'desa.id', 'profil_users.nama_pkk');

            if ($request->date_start && $request->date_end) {
                $query->whereBetween('laporan.date', [$request->date_start, $request->date_end]);
            }

            $data = $query->orderBy('jumlah', 'desc')->get();

            $data->each(function ($item, $key) use ($data) {
                $item->peringkat = $data->search(function ($searchItem) use ($item) {
                    return $searchItem->jumlah == $item->jumlah;
                }) + 1;
            });

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
                    $jumlah =  '<center><span class="badge badge-danger">' . $data->jumlah . '</span></center>';
                    return $jumlah;
                })
                ->addColumn('peringkat', function ($data) {
                    $badgeClass = 'badge-secondary';
                    $icon = '';
                    if ($data->peringkat == 1) {
                        $badgeClass = 'badge-success';
                        $icon = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
                    } elseif ($data->peringkat == 2) {
                        $badgeClass = 'badge-warning';
                        $icon = '<i class="fa fa-star"></i><i class="fa fa-star"></i>';
                    } elseif ($data->peringkat == 3) {
                        $icon = '<i class="fa fa-star"></i>';
                        $badgeClass = 'badge-danger';
                    }
                    $peringkat = '<center><span class="badge badge-lg ' . $badgeClass . '">' . $icon . ' Ranking : ' . $data->peringkat . '</span></center>';
                    return $peringkat;
                })
                ->addColumn('detail', function ($row) use ($request) {
                    return '<center><a href="javascript:void(0)" data-id="' . $row->desa_id . '" data-name="' . $row->nama_pkk . '" data-date-start="' . $request->date_start . '" data-date-end="' . $request->date_end . '" class="btn btn-primary btn-xs detail">Detail</a></center>';
                })
                ->rawColumns(['pkk', 'jumlah', 'peringkat', 'detail'])
                ->make(true);
        }

        return view('ranking.desa', compact('menu', 'submenu'));
    }

    public function download(Request $request)
    {
        $filePath = storage_path('app/public/buku-panduan/Buku_Panduan_Aplikasi_E-Reporting.pdf');
        if (file_exists($filePath)) {
            $fileName = 'Buku_Panduan_Aplikasi_E-Reporting.pdf';

            return response()->download($filePath, $fileName);
        }
        return redirect()->route('dashboard')->with('error', 'Buku Panduan tidak ditemukan.');
    }
}
