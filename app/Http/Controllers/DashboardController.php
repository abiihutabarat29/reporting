<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Laporan;
use App\Models\ProfilUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menu = 'Dashboard';
        $userskec = User::where('role', 2)->count();
        $usersdesa = User::where('role', 3)->count();
        $bidang = Bidang::count();
        if (Auth::user()->role == 1) {
            $data = Laporan::where('user_id', Auth::user()->id)->limit(20)->latest()->get();
            $laporan = Laporan::where('user_id', Auth::user()->id)->count();
            return view('dashboard.admin', compact('menu', 'userskec', 'usersdesa', 'bidang', 'laporan', 'data'));
        } elseif (Auth::user()->role == 2) {
            $banner = ProfilUser::select('banner')->where('user_id', Auth::user()->id)->first();
            $data = Laporan::where('user_id', Auth::user()->id)->limit(20)->latest()->get();
            return view('dashboard.kecamatan', compact('menu', 'data', 'banner'));
        } elseif (Auth::user()->role == 3) {
            $banner = ProfilUser::select('banner')->where('user_id', Auth::user()->id)->first();
            $data = Laporan::where('user_id', Auth::user()->id)->limit(20)->latest()->get();
            return view('dashboard.desa', compact('menu', 'data', 'banner'));
        }
    }
}
