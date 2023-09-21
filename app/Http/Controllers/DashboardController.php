<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
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
        // $laporan = Laporan::count();
        if (Auth::user()->role == 1) {
            return view('dashboard.admin', compact('menu', 'userskec', 'usersdesa', 'bidang'));
        } elseif (Auth::user()->role == 2) {
            return view('dashboard.kecamatan', compact('menu'));
        } elseif (Auth::user()->role == 3) {
            return view('dashboard.desa', compact('menu'));
        }
    }
}
