<?php

namespace App\Http\Controllers;

use App\Models\ProfilUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function index()
    {
        $menu = "Profil";
        $user = User::where('id', Auth::user()->id)->first();
        return view('user.profil', compact('menu', 'user'));
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
}
