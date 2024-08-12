<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckProfil
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user()->profil;
        if (!$user->profilIsComplete()) {
            return redirect()->route('profil');
        }
        return $next($request);
    }
}
